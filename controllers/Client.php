<?php
/**
 * Client controller
 *
 * @author Sandro Alves Peres <sandroalvesperes@yahoo.com.br>
 * @see http://www.zend.com/en/yellow-pages/ZEND022656
 */
class Client extends BaseController
{

    public function get_wait()
    {
        $this->validateSession();
        $view = View::instance();
        $view->render('waiting');
    }

    public function get_supportOffline()
    {
        try
        {
            if( isset($_SESSION['client_user']) )
            {
                $chat = $this->orm->chat[ $_SESSION['client_user']['id_chat'] ];

                if( $chat['closed'] )
                {
                    unset($_SESSION['client_user']);
                    Header::redirect(URL::baseUrl());
                }
                elseif( $chat['id_support_user'] )
                {
                    Header::redirect(URL::baseUrl() . '/conversation');
                }
                else
                {
                    Header::redirect(URL::baseUrl() . '/client/wait');
                }
            }

            $view = View::instance();
            $view->render('offline');
        }
        catch( Exception $e )
        {
            $view = View::instance();
            $view->render('error');
        }
    }

    public function post_register()
    {
        Header::json();

        $name    = trim($this->request->post('name'));
        $email   = trim($this->request->post('email', FILTER_VALIDATE_EMAIL));
        $sex     = trim($this->request->post('sex'));
        $subject = trim($this->request->post('subject'));

        if( ctype_digit($name) || !preg_match('@^[\w\s]+$@u', $name) )
        {
            die('{ "ok" : false, "msg" : "Invalid input" }');
        }

        if( !$email || !$subject || ($sex != 'M' && $sex != 'F') )
        {
            die('{ "ok" : false, "msg" : "Invalid input" }');
        }

        try
        {
            $clientUser = $this->orm->client_user();
            $clientUser->select('id_client_user, name, email');
            $clientUser->where('name', $name);
            $clientUser->and('email', $email);
            $clientUser->and('sex', $sex);
            $clientUser = $clientUser->fetch();

            $this->orm->transaction = 'begin';

            if( $clientUser )
            {
                $clientUser->update(array(
                    'last_activity' => new NotORM_Literal('NOW()')
                ));
            }
            else
            {
                $clientUser = $this->orm->client_user()->insert(array(
                    'name'          => $name,
                    'email'         => $email,
                    'sex'           => $sex,
                    'last_activity' => new NotORM_Literal('NOW()')
                ));
            }

            $chat = $this->orm->chat()->insert(array(
                'id_client_user' => $clientUser['id_client_user'],
                'subject'        => $subject
            ));

            $_SESSION['client_user'] = array(
                'id_chat' => $chat['id_chat'],
                'id_user' => $clientUser['id_client_user'],
                'name'    => $clientUser['name'],
                'email'   => $clientUser['email']
            );

            $this->orm->transaction = 'commit';

            unset($_SESSION['support_user'], $_SESSION['chat_evaluate']);

            die('{ "ok" : true }');
        }
        catch( Exception $e )
        {
            @$this->orm->transaction = 'rollback';
            die('{ "ok" : false, "msg" : "Error occurred" }');
        }
    }

    public function post_waitUpdates()
    {
        $this->validateSession();
        Header::json();

        try
        {
            # CHAT STATUS
            # ------------------------------------------------------------------

            $param = $this->orm->param();
            $param->select('value');
            $param->where('name', 'STATUS');

            $status = $param->fetch();

            # OPERATORS ONLINE
            # ------------------------------------------------------------------

            $operators = $this->orm->support_user();
            $operators->where('active', 1);
            $operators->and('online', 1);

            $operatorsOnline = $operators->count('id_support_user');

            # CLIENTS WAITING TO CHAT
            # ------------------------------------------------------------------

            $chat = $this->orm->chat();
            $chat->where('closed IS NULL');
            $chat->and('id_support_user IS NULL');

            $clientsWaiting = $chat->count('id_chat');

            # OPERATOR OPENED THE CHAT / CHAT CLOSED
            # ------------------------------------------------------------------

            $chat = $this->orm->chat();
            $chat->select('id_support_user, closed_by');
            $chat->where('id_chat', $_SESSION['client_user']['id_chat']);

            $chatClient = $chat->fetch();

            $supportResponded = ($chatClient['id_support_user'] ? true : false);
            $chatClosed       = ($chatClient['closed_by'] ? true : false);

            if( $status['value'] == 0 || $operatorsOnline == 0 )
            {
                $this->orm->chat[ $_SESSION['client_user']['id_chat'] ]->update(array(
                    'closed'    => new NotORM_Literal('NOW()'),
                    'closed_by' => 'System'
                ));
            }

            if( $status['value'] == 0 || $operatorsOnline == 0 || $chatClosed )
            {
                unset($_SESSION['client_user']);
            }

            print json_encode(array(
                'ok'               => true,
                'chatStatus'       => $status['value'],
                'operatorsOnline'  => $operatorsOnline,
                'clientsWaiting'   => $clientsWaiting,
                'supportResponded' => $supportResponded,
                'chatClosed'       => $chatClosed
            ));
        }
        catch( Exception $e )
        {
            print '{ "ok" : false, "msg" : "Error occurred" }';
        }
    }

    public function post_sendEmail()
    {
        Header::json();

        $name    = trim($this->request->post('name'));
        $email   = trim($this->request->post('email', FILTER_VALIDATE_EMAIL));
        $sex     = trim($this->request->post('sex'));
        $subject = trim($this->request->post('subject'));
        $message = trim($this->request->post('message'));

        if( ctype_digit($name) || !preg_match('@^[\w\s]+$@u', $name) )
        {
            die('{ "ok" : false, "msg" : "Invalid input" }');
        }

        if( !$email || !$subject || !$message || ($sex != 'M' && $sex != 'F') )
        {
            die('{ "ok" : false, "msg" : "Invalid input" }');
        }

        try
        {
            $view = View::instance();
            $view->name    = $name;
            $view->email   = $email;
            $view->sex     = str_replace(array('M', 'F'), array('Male', 'Female'), $sex);
            $view->subject = $subject;
            $view->message = $message;

            $emailBody = $view->render('email', null, true);

            $to = $this->orm->param[ array('name' => 'EMAIL') ]['value'];

            $headers = array(
                'MIME-Version: 1.0',
                'Content-Type: text/html; charset=utf-8',
                "From: {$name} <{$email}>",
                "Reply-to: {$name} <{$email}>",
                'X-Priority: 1',
                'X-Mailer: PHP/' . phpversion()
            );

            $sent = @mail($to, $subject, $emailBody, implode("\r\n", $headers));

            if( $sent )
            {
                die('{ "ok" : true }');
            }
            else
            {
                die('{ "ok" : false, "msg" : "Error senting the e-mail" }');
            }
        }
        catch( Exception $e )
        {
            die('{ "ok" : false, "msg" : "Error occurred" }');
        }
    }

    public function post_cancel()
    {
        $this->validateSession();
        Header::json();

        try
        {
            $this->orm->transaction = 'begin';

            $this->orm->client_user[ $_SESSION['client_user']['id_user'] ]->update(array(
                'last_activity' => new NotORM_Literal('NOW()')
            ));

            $this->orm->chat[ $_SESSION['client_user']['id_chat'] ]->update(array(
                'closed'    => new NotORM_Literal('NOW()'),
                'closed_by' => 'Client'
            ));

            $this->orm->transaction = 'commit';

            print '{ "ok" : true }';
        }
        catch( Exception $e )
        {
            @$this->orm->transaction = 'rollback';
            print '{ "ok" : false, "msg" : "Error occurred" }';
        }
    }

    public function post_information()
    {
        Header::json();

        try
        {
            $email = $this->request->post('email', FILTER_VALIDATE_EMAIL);

            $clientUser = $this->orm->client_user();
            $clientUser->select('name, sex');
            $clientUser->where('email', $email);
            $clientUser = $clientUser->fetch();

            if( !$clientUser )
            {
                exit('{ "ok" : false }');
            }

            print json_encode(array(
                'ok'   => true,
                'name' => $clientUser['name'],
                'sex'  => $clientUser['sex']
            ));
        }
        catch( Exception $e )
        {
            print '{ "ok" : false }';
        }
    }

    /**
     * Validates the user session to allow or deny access
     *
     * @access private
     * @return void
     */
    private function validateSession()
    {
        if( !UserAccess::isClient() )
        {
            if( $this->request->isAjax() )
            {
                die('{ "ok" : false, "msg" : "Access denied" }');
            }
            else
            {
                Header::redirect(URL::baseUrl());
            }
        }
    }

}
?>