<?php
/**
 * Support controller
 *
 * @author Sandro Alves Peres <sandroalvesperes@yahoo.com.br>
 * @see http://www.zend.com/en/yellow-pages/ZEND022656
 */
class Support extends BaseController
{

    public function before()
    {
        $userAccess = new UserAccess();

        if( !$userAccess->isSupport() )
        {
            if( $this->request->isAjax() )
            {
                die('{ "ok" : false, "msg" : "Access denied" }');
            }
            else
            {
                Header::redirect(URL::baseUrl() . '/login');
            }
        }
    }

    public function get_index()
    {
        $view = View::instance();

        try
        {
            $param = $this->orm->param();
            $param->select('value');
            $param->where('name', 'STATUS');
            $rowParam = $param->fetch();

            $userOnline = $this->orm->support_user[ $_SESSION['support_user']['id_user'] ]['online'];

            $pkSupportUserAccess = array('id_support_user' => $_SESSION['support_user']['id_user']);
            $supportUserAccess   = $this->orm->support_user_access[ $pkSupportUserAccess ];

            $view->supportStatus       = (bool)$rowParam['value'];
            $view->userStatus          = (bool)$userOnline;
            $view->accessMaintainUser  = $supportUserAccess['maintain_user'];
            $view->accessSupportStatus = $supportUserAccess['support_status'];
            $view->render('support');
        }
        catch( Exception $e )
        {
            $view->render('error');
        }
    }

    public function post_register()
    {
        Header::json();

        try
        {
            $chatId = $this->request->post('idChat', FILTER_VALIDATE_INT);
            $chat   = $this->orm->chat[ $chatId ];

            if( !empty($chat['id_support_user']) && $chat['id_support_user'] != $_SESSION['support_user']['id_user'] )
            {
                die('{ "ok" : false, "msg" : "User doesn\'t belong to this chat" }');
            }

            $this->orm->transaction = 'begin';

            $chat->update(array(
                'id_support_user' => $_SESSION['support_user']['id_user']
            ));

            $this->orm->support_user[ $_SESSION['support_user']['id_user'] ]->update(array(
                'last_activity' => new NotORM_Literal('NOW()')
            ));

            $this->orm->transaction = 'commit';

            print '{ "ok" : true }';
        }
        catch( Exception $e )
        {
            print '{ "ok" : false, "msg" : "Error occurred" }';
        }
    }

    public function post_clientsList()
    {
        try
        {
            $chat = $this->orm->chat();
            $chat->select('id_chat, id_client_user, id_support_user, subject');
            $chat->select('TIMESTAMPDIFF(MINUTE, created, NOW()) AS waiting_minutes');
            $chat->select('LEFT(SEC_TO_TIME(TIMESTAMPDIFF(SECOND, created, NOW())), 5) AS waiting_time');
            $chat->where('closed IS NULL');
            $chat->and('(id_support_user IS NULL')->or('id_support_user', $_SESSION['support_user']['id_user'])->where(')');

            $supportStatus  = $this->orm->param[ array('name' => 'STATUS') ]['value'];
            $waitingTooMuch = $this->orm->param[ array('name' => 'WAITING_TOO_MUCH') ]['value'];

            $clients      = array();
            $userOccupied = false;

            foreach( $chat as $row )
            {
                $clients[] = array(
                    'id_chat'           => $row['id_chat'],
                    'id_support_user'   => $row['id_support_user'],
                    'subject'           => $row['subject'],
                    'waiting_too_much'  => ($row['waiting_minutes'] > $waitingTooMuch),
                    'waiting_time'      => $row['waiting_time'],
                    'client_user_name'  => $row->client_user['name'],
                    'client_user_sex'   => str_replace(array('M', 'F'), array('male', 'female'), $row->client_user['sex']),
                    'client_user_email' => $row->client_user['email'],
                );

                if( $row['id_support_user'] )
                {
                    $userOccupied = true;
                }
            }

            $view = View::instance();
            $view->clients       = $clients;
            $view->supportStatus = $supportStatus;
            $view->userOccupied  = $userOccupied;
            $view->render('partial/clients-list');
        }
        catch( Exception $e )
        {
            print '
                <tr>
                    <td colspan="6">
                        <div class="result-error">Error occurred</div>
                    </td>
                </tr>
            ';
        }
    }

    public function post_disconnectClient()
    {
        Header::json();

        try
        {
            $idChat = $this->request->post('chat_id', FILTER_VALIDATE_INT);

            $this->orm->transaction = 'begin';

            $this->orm->chat[ $idChat ]->update(array(
                'id_support_user' => $_SESSION['support_user']['id_user'],
                'closed'          => new NotORM_Literal('NOW()'),
                'closed_by'       => 'Support'
            ));

            $this->orm->support_user[ $_SESSION['support_user']['id_user'] ]->update(array(
                'last_activity' => new NotORM_Literal('NOW()')
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

    public function post_supportStatus()
    {
        Header::json();

        try
        {
            $pkSupportUserAccess = array('id_support_user' => $_SESSION['support_user']['id_user']);
            $supportUserAccess   = $this->orm->support_user_access[ $pkSupportUserAccess ];

            if( !$supportUserAccess['support_status'] )
            {
                exit('{ "ok" : false, "msg" : "Access denied" }');
            }

            $status = $this->request->post('status', FILTER_VALIDATE_BOOLEAN);

            $param = $this->orm->param();
            $param->where('name', 'STATUS');
            $row = $param->fetch();

            $row->update(array(
                'value' => $status
            ));

            print '{ "ok" : true }';
        }
        catch( Exception $e )
        {
            print '{ "ok" : false, "msg" : "Error occurred" }';
        }
    }

    public function post_userStatus()
    {
        Header::json();

        try
        {
            $status = $this->request->post('status', FILTER_VALIDATE_BOOLEAN);

            $this->orm->support_user[ $_SESSION['support_user']['id_user'] ]->update(array(
                'online'        => $status,
                'last_activity' => new NotORM_Literal('NOW()')
            ));

            print '{ "ok" : true }';
        }
        catch( Exception $e )
        {
            print '{ "ok" : false, "msg" : "Error occurred" }';
        }
    }

    public function post_checkSupportInactivity()
    {
        Header::json();

        try
        {
            $timeLimit = $this->orm->param[ array('name' => 'SET_OFFLINE_IN') ]['value'];

            $supportUser = $this->orm->support_user();
            $supportUser->where('online', 1);
            $supportUser->and('TIMESTAMPDIFF(MINUTE, last_activity, NOW()) > ?', $timeLimit);
            $supportUser->update(array(
                'typing' => 0,
                'online' => 0
            ));

            print '{ "ok" : true }';
        }
        catch( Exception $e )
        {
            print '{ "ok" : false }';
        }
    }

}
?>