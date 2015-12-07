<?php
/**
 * Conversation controller
 *
 * @author Sandro Alves Peres <sandroalvesperes@yahoo.com.br>
 * @see http://www.zend.com/en/yellow-pages/ZEND022656
 */
class Conversation extends BaseController
{

    public function before()
    {
        if( strtolower($this->getAction()) == 'evaluate' && !isset($_SESSION['chat_evaluate']) )
        {
            Header::redirect(URL::baseUrl());
        }

        if( !in_array(strtolower($this->getAction()), array('evaluate', 'evaluatesupport')) )
        {
            $userAccess = new UserAccess();

            if( !$userAccess->isValid() )
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

    public function get_index()
    {
        try
        {
            $idChat   = @$this->request->get('id', FILTER_VALIDATE_INT);
            $userChat = new UserChat($this->orm, $idChat);

            if( !$userChat->isValid() )
            {
                if( $userChat->isSupport() )
                {
                    Header::redirect(URL::baseUrl() . '/login');
                }
                else
                {
                    Header::redirect(URL::baseUrl());
                }
            }

            $this->orm->{$userChat->table}[ $userChat->idUser ]->update(array(
                'typing'        => 0,
                'last_activity' => new NotORM_Literal('NOW()')
            ));

            $userChat->talkingTo()->sex = str_replace(
                array('M', 'F'),
                array('male', 'female'),
                $userChat->talkingTo()->sex
            );

            # Get the chat messages
            # ------------------------------------------------------------------

            $chatMessage = $this->orm->chat_message();
            $chatMessage->select('id_chat_message, created, message, sent_by');
            $chatMessage->where('id_chat', $userChat->idChat);
            $chatMessage->order('id_chat_message ASC');

            $messages = array();

            $format = new DateTimeFormat();
            $format->setSupHtmlSuffix(true);

            foreach( $chatMessage as $message )
            {
                $format->setValue($message['created']);

                $messages[] = array(
                    'who'             => ($userChat->type == $message['sent_by'] ? 'me' : 'you'),
                    'id_chat_message' => $message['id_chat_message'],
                    'message'         => nl2br(htmlspecialchars($message['message'])),
                    'datetime'        => $format->format()
                );
            }

            $view = View::instance();
            $view->idChat    = $userChat->idChat;
            $view->messages  = $messages;
            $view->talkingTo = $userChat->talkingTo();
            $view->render('conversation');
        }
        catch( Exception $e )
        {
            $view = View::instance();
            $view->render('error');
        }
    }

    public function get_evaluate()
    {
        $view = View::instance();
        $view->render('evaluate');
    }

    public function post_sendMessage()
    {
        Header::json();

        try
        {
            $idChat  = $this->request->post('chat_id', FILTER_VALIDATE_INT);
            $message = trim($this->request->post('message'));

            if( $message === '' )
            {
                throw new Exception('Invalid message');
            }

            $userChat = new UserChat($this->orm, $idChat);

            if( !$userChat->isValid() )
            {
                exit('{ "ok" : false, "msg" : "Invalid chat" }');
            }

            $this->orm->{$userChat->table}[ $userChat->idUser ]->update(array(
                'typing'        => 0,
                'last_activity' => new NotORM_Literal('NOW()')
            ));

            $objMessage = $this->orm->chat_message()->insert(array(
                'id_chat'    => $idChat,
                'sent_by'    => $userChat->type,
                'sent_by_id' => $userChat->idUser,
                'message'    => $message
            ));

            $format = new DateTimeFormat($this->orm->chat_message[ $objMessage['id_chat_message'] ]['created']);
            $format->setSupHtmlSuffix(true);

            print json_encode(array(
                'ok'         => true,
                'message_id' => $objMessage['id_chat_message'],
                'datetime'   => $format->format()
            ));
        }
        catch( Exception $e )
        {
            print '{ "ok" : false, "msg" : "Not sent" }';
        }
    }

    public function post_messages()
    {
        try
        {
            $idChat        = $this->request->post('chat_id', FILTER_VALIDATE_INT);
            $lastMessageId = $this->request->post('last_message_id', FILTER_VALIDATE_INT);

            $userChat = new UserChat($this->orm, $idChat);

            if( !$userChat->isValid() )
            {
                exit;
            }

            $chatMessage = $this->orm->chat_message();
            $chatMessage->select('id_chat_message, created, message');
            $chatMessage->where('id_chat', $idChat);
            $chatMessage->and('id_chat_message > ?', $lastMessageId);
            $chatMessage->and('sent_by', $userChat->talkingTo()->type);
            $chatMessage->and('sent_by_id', $userChat->talkingTo()->idUser);
            $chatMessage->order('id_chat_message ASC');

            $messages = array();

            $format = new DateTimeFormat();
            $format->setSupHtmlSuffix(true);

            foreach( $chatMessage as $message )
            {
                $format->setValue($message['created']);

                $messages[] = array(
                    'id_chat_message' => $message['id_chat_message'],
                    'message'         => nl2br(htmlspecialchars($message['message'])),
                    'datetime'        => $format->format()
                );
            }

            $view = View::instance();
            $view->messages = $messages;
            $view->render('partial/chat-messages');
        }
        catch( Exception $e ){}
    }

    public function post_typing()
    {
        Header::json();

        try
        {
            $typing   = $this->request->post('typing', FILTER_VALIDATE_BOOLEAN);
            $idChat   = $this->request->post('chat_id', FILTER_VALIDATE_INT);
            $userChat = new UserChat($this->orm, $idChat);

            if( !$userChat->isValid() )
            {
                exit('{ "ok" : false, "msg" : "Invalid chat" }');
            }

            $this->orm->{$userChat->table}[ $userChat->idUser ]->update(array(
                'typing'        => $typing,
                'last_activity' => new NotORM_Literal('NOW()')
            ));

            print '{ "ok" : true }';
        }
        catch( Exception $e )
        {
            print '{ "ok" : false, "msg" : "Not sent" }';
        }
    }

    public function post_typingCheck()
    {
        Header::json();

        try
        {
            $idChat   = $this->request->post('chat_id', FILTER_VALIDATE_INT);
            $userChat = new UserChat($this->orm, $idChat);

            if( !$userChat->isValid() )
            {
                exit('{ "ok" : false, "msg" : "Invalid chat" }');
            }

            $typing = (int)$this->orm->{$userChat->talkingTo()->table}[ $userChat->talkingTo()->idUser ]['typing'];

            print '{ "ok" : true, "typing" : ' . $typing . ' }';
        }
        catch( Exception $e )
        {
            print '{ "ok" : false, "msg" : "Not sent" }';
        }
    }

    public function post_quit()
    {
        Header::json();

        try
        {
            $idChat   = $this->request->post('chat_id', FILTER_VALIDATE_INT);
            $userChat = new UserChat($this->orm, $idChat);

            if( !$userChat->isValid() )
            {
                exit('{ "ok" : false, "msg" : "Invalid chat" }');
            }

            $this->orm->chat[ $idChat ]->update(array(
                'closed'    => new NotORM_Literal('NOW()'),
                'closed_by' => $userChat->type
            ));

            if( $userChat->isClient() )
            {
                unset($_SESSION['client_user']);

                $_SESSION['chat_evaluate'] = array(
                    'id_chat' => $userChat->idChat,
                    'id_user' => $userChat->idUser
                );
            }

            print '{ "ok" : true, "type" : "' . $userChat->type . '" }';
        }
        catch( Exception $e )
        {
            print '{ "ok" : false, "msg" : "Error occurred" }';
        }
    }

    public function post_closedCheck()
    {
        Header::json();

        try
        {
            $idChat   = $this->request->post('chat_id', FILTER_VALIDATE_INT);
            $userChat = new UserChat($this->orm, $idChat);

            if( !$userChat->isValid() )
            {
                exit('{ "ok" : false, "msg" : "Invalid chat" }');
            }

            $chat = $this->orm->chat[ $idChat ];

            if( $userChat->isClient() && $chat['closed'] )
            {
                unset($_SESSION['client_user']);

                $_SESSION['chat_evaluate'] = array(
                    'id_chat' => $userChat->idChat,
                    'id_user' => $userChat->idUser
                );
            }

            print json_encode(array(
                'ok'        => true,
                'type'      => $userChat->type,
                'closed'    => ($chat['closed'] ? true : false),
                'closed_by' => $chat['closed_by']
            ));
        }
        catch( Exception $e )
        {
            print '{ "ok" : false, "msg" : "Error occurred" }';
        }
    }

    public function post_evaluateSupport()
    {
        Header::json();

        try
        {
            $rate = $this->request->post('rate', FILTER_VALIDATE_INT);

            $this->orm->chat[ $_SESSION['chat_evaluate']['id_chat'] ]->update(array(
                'rate' => $rate
            ));

            unset($_SESSION['chat_evaluate']);

            print '{ "ok" : true }';
        }
        catch( Exception $e )
        {
            print '{ "ok" : false, "msg" : "Error occurred" }';
        }
    }

}
?>