<?php
/**
 * Login controller
 *
 * @author Sandro Alves Peres <sandroalvesperes@yahoo.com.br>
 * @see http://www.zend.com/en/yellow-pages/ZEND022656
 */
class Login extends BaseController
{

    public function get_index()
    {
        $userAccess = new UserAccess();

        if( $userAccess->isSupport() )
        {
            Header::redirect(URL::baseUrl() . '/support');
        }

        $view = View::instance();
        $view->email = @$this->request->cookie('email');
        $view->render('login');
    }

    public function post_signIn()
    {
        Header::json();

        $email         = $this->request->post('email', FILTER_VALIDATE_EMAIL);
        $password      = $this->request->post('password');
        $rememberEmail = $this->request->post('remember_email', FILTER_VALIDATE_BOOLEAN);

        if( !$email || !$password )
        {
            die('{ "ok" : false, "msg" : "Invalid login" }');
        }

        try
        {
            $user = $this->orm->support_user();
            $user->select('id_support_user, name, sex');
            $user->where('email', $email);
            $user->and('password', md5($password));
            $user->and('active', 1);

            $row = $user->fetch();

            if( $row )
            {
                $row->update(array(
                    'online'        => 0,
                    'last_activity' => new NotORM_Literal('NOW()')
                ));

                unset($_SESSION['client_user']);

                $_SESSION['support_user'] = array(
                    'id_user' => $row['id_support_user'],
                    'name'    => $row['name'],
                    'sex'     => $row['sex']
                );

                if( $rememberEmail )
                {
                    $timeCookie = (time() + 60 * 60 * 24 * 20); # 20 days
                    setcookie('email', $email, $timeCookie, null, $_SERVER['HTTP_HOST']);
                }
                else
                {
                    $timeCookie = (time() - 60 * 60 * 24); # Delete
                    setcookie('email', $email, $timeCookie, null, $_SERVER['HTTP_HOST']);
                }

                print '{ "ok" : true }';
            }
            else
            {
                print '{ "ok" : false, "msg" : "Invalid login" }';
            }
        }
        catch( Exception $e )
        {
            print '{ "ok" : false, "msg" : "Error occurred" }';
        }
    }

    public function get_signOut()
    {
        try
        {
            $idUser = @$_SESSION['support_user']['id_user'];

            if( $idUser )
            {
                $this->orm->support_user[ $idUser ]->update(array(
                    'online'        => 0,
                    'typing'        => 0,
                    'last_activity' => new NotORM_Literal('NOW()')
                ));
            }

            if( isset($_SESSION['support_user']) && count($_SESSION) == 1 )
            {
                unset($_SESSION['support_user']);
                session_unset();
                session_destroy();
            }
            else
            {
                unset($_SESSION['support_user']);
            }

            Header::redirect(URL::baseUrl() . '/login');
        }
        catch( Exception $e )
        {
            $view = View::instance();
            $view->render('error');
        }
    }

}
?>