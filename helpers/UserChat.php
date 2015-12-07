<?php
/**
 * Class used to wrap the chat user, regardless
 * whether it's a client user or a support user
 *
 * @author Sandro Alves Peres <sandroalvesperes@yahoo.com.br>
 * @see http://www.zend.com/en/yellow-pages/ZEND022656
 */
class UserChat
{

    /**
     * @var ORM
     * @access private
     */
    private $orm;

    /**
     * @var ArrayObject
     * @access private
     */
    private $talkingTo;

    /**
     * @var int
     * @access public
     */
    public $idUser;

    /**
     * @var int
     * @access public
     */
    public $idChat;

    /**
     * @var string
     * @access public
     */
    public $name;

    /**
     * @var string
     * @access public
     */
    public $sex;

    /**
     * @var string - (M/F)
     * @access public
     */
    public $email;

    /**
     * @var string - (Client/Support)
     * @access public
     */
    public $type;

    /**
     * ORM purposes
     *
     * @var string
     * @access public
     */
    public $table;

    /**
     * Constructor method
     *
     * @param ORM $orm
     * @param int $idChat = null
     * @access public
     * @return void
     */
    public function __construct( ORM $orm, $idChat = null )
    {
        $this->orm    = $orm;
        $this->idChat = $idChat;

        $this->talkingTo = new ArrayObject();
        $this->talkingTo->setFlags(ArrayObject::ARRAY_AS_PROPS);

        $this->parseInformation();
    }

    /**
     * Checks whether the chat id is valid
     * or not according to the session
     *
     * @access public
     * @return boolean
     */
    public function isValid()
    {
        if( !is_numeric($this->idChat) )
        {
            return false;
        }

        if( isset($_SESSION['client_user']) )
        {
            if( $this->idChat != $_SESSION['client_user']['id_chat'] )
            {
                return false;
            }
        }
        else
        {
            if( $_SESSION['support_user']['id_user'] != $this->orm->chat[ $this->idChat ]['id_support_user'] )
            {
                return false;
            }
        }

        return true;
    }

    /**
     * Returns the user who is talking to the current user.
     * When the current user is a client, it returns the
     * support user information and vice-versa
     *
     * @access public
     * @return ArrayObject
     */
    public function talkingTo()
    {
        return $this->talkingTo;
    }

    /**
     * Check whether the current user is the support or not
     *
     * @access public
     * @return boolean
     */
    public function isSupport()
    {
        return ('Support' == $this->type);
    }

    /**
     * Check whether the current user is the client or not
     *
     * @access public
     * @return boolean
     */
    public function isClient()
    {
        return ('Client' == $this->type);
    }

    /**
     * Stores the current user's information
     * according to its type (Client or Support)
     *
     * @access private
     * @return void
     */
    private function parseInformation()
    {
        if( isset($_SESSION['client_user']) ) # Client
        {
            $this->idChat = $_SESSION['client_user']['id_chat'];
            $chat         = $this->orm->chat[ $this->idChat ];

            $this->idUser = $_SESSION['client_user']['id_user'];
            $this->name   = trim($chat->client_user['name']);
            $this->sex    = $chat->client_user['sex'];
            $this->email  = trim($chat->client_user['email']);
            $this->type   = 'Client';
            $this->table  = 'client_user';

            $this->talkingTo()->idUser = $chat['id_support_user'];
            $this->talkingTo()->name   = trim($chat->support_user['name']);
            $this->talkingTo()->sex    = $chat->support_user['sex'];
            $this->talkingTo()->email  = trim($chat->support_user['email']);
            $this->talkingTo()->type   = 'Support';
            $this->talkingTo()->table  = 'support_user';
        }
        elseif( isset($_SESSION['support_user']) ) # Support
        {
            $chat = $this->orm->chat[ $this->idChat ];

            $this->idUser = $_SESSION['support_user']['id_user'];
            $this->name   = trim($chat->support_user['name']);
            $this->sex    = $chat->support_user['sex'];
            $this->email  = trim($chat->support_user['email']);
            $this->type   = 'Support';
            $this->table  = 'support_user';

            $this->talkingTo()->idUser = $chat['id_client_user'];
            $this->talkingTo()->name   = trim($chat->client_user['name']);
            $this->talkingTo()->sex    = $chat->client_user['sex'];
            $this->talkingTo()->email  = trim($chat->client_user['email']);
            $this->talkingTo()->type   = 'Client';
            $this->talkingTo()->table  = 'client_user';
        }
    }

}
?>