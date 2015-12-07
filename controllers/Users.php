<?php
/**
 * Users controller
 *
 * @author Sandro Alves Peres <sandroalvesperes@yahoo.com.br>
 * @see http://www.zend.com/en/yellow-pages/ZEND022656
 */
class Users extends BaseController
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

        try
        {
            $pkSupportUserAccess = array('id_support_user' => $_SESSION['support_user']['id_user']);
            $supportUserAccess   = $this->orm->support_user_access[ $pkSupportUserAccess ];

            if( !$supportUserAccess['maintain_user'] )
            {
                $view = View::instance();
                $view->message = 'Access denied';
                $view->render('error');
                die;
            }
        }
        catch( Exception $e )
        {
            $view = View::instance();
            $view->message = 'Error occurred';
            $view->render('error');
            die;
        }
    }

    public function get_index()
    {
        $view = View::instance();
        $view->render('users/index');
    }

    public function get_newUser()
    {
        $view = View::instance();
        $view->render('users/new-user');
    }

    public function get_editUser()
    {
        try
        {
            $idSupportUser     = $this->request->get('id', FILTER_VALIDATE_INT);
            $supportUser       = $this->orm->support_user[ $idSupportUser ];
            $supportUserAccess = $supportUser->support_user_access()->via('id_support_user')->fetch();

            $view = View::instance();
            $view->user = array(
                'name'           => $supportUser['name'],
                'email'          => $supportUser['email'],
                'sex'            => $supportUser['sex'],
                'active'         => $supportUser['active'],
                'maintain_user'  => $supportUserAccess['maintain_user'],
                'support_status' => $supportUserAccess['support_status']
            );
            $view->render('users/edit-user');
        }
        catch( Exception $e )
        {
            $view = View::instance();
            $view->render('error');
        }
    }

    public function post_newUser()
    {
        Header::json();

        try
        {
            $name     = $this->request->post('name');
            $email    = $this->request->post('email', FILTER_VALIDATE_EMAIL);
            $sex      = $this->request->post('sex');
            $password = $this->request->post('password');
            $active   = $this->request->post('active', FILTER_VALIDATE_BOOLEAN);

            $accessMaintainUser  = $this->request->post('access_maintain_user', FILTER_VALIDATE_BOOLEAN);
            $accessSupportStatus = $this->request->post('access_support_status', FILTER_VALIDATE_BOOLEAN);

            # Check whether the e-mail already exists
            # ------------------------------------------------------------------

            $supportUser = $this->orm->support_user();
            $supportUser->where('email', $email);

            if( $supportUser->count('*') > 0 )
            {
                exit('{ "ok" : false, "msg" : "E-mail already exists" }');
            }

            $this->orm->transaction = 'begin';

            $supportUser = $this->orm->support_user()->insert(array(
                'name'     => $name,
                'email'    => $email,
                'sex'      => $sex,
                'password' => md5($password),
                'active'   => $active
            ));

            $this->orm->support_user_access()->insert(array(
                'id_support_user' => $supportUser['id_support_user'],
                'maintain_user'   => $accessMaintainUser,
                'support_status'  => $accessSupportStatus
            ));

            $this->orm->transaction = 'commit';

            print '{ "ok" : true }';
        }
        catch( Exception $e )
        {
            print '{ "ok" : false, "msg" : "Error occurred" }';
        }
    }

    public function post_editUser()
    {
        Header::json();

        try
        {
            $idSupportUser = $this->request->post('id_support_user', FILTER_VALIDATE_INT);
            $name          = $this->request->post('name');
            $email         = $this->request->post('email', FILTER_VALIDATE_EMAIL);
            $sex           = $this->request->post('sex');
            $password      = $this->request->post('password');
            $active        = $this->request->post('active', FILTER_VALIDATE_BOOLEAN);

            $accessMaintainUser  = $this->request->post('access_maintain_user', FILTER_VALIDATE_BOOLEAN);
            $accessSupportStatus = $this->request->post('access_support_status', FILTER_VALIDATE_BOOLEAN);

            # Check whether the e-mail already exists
            # ------------------------------------------------------------------

            $supportUser = $this->orm->support_user();
            $supportUser->where('email', $email);
            $supportUser->and('id_support_user <> ?', $idSupportUser);

            if( $supportUser->count('*') > 0 )
            {
                exit('{ "ok" : false, "msg" : "E-mail already exists" }');
            }

            $this->orm->transaction = 'begin';

            $this->orm->support_user[ $idSupportUser ]->update(array(
                'name'     => $name,
                'email'    => $email,
                'sex'      => $sex,
                'password' => (empty($password) ? new NotORM_Literal('password') : md5($password)),
                'active'   => $active
            ));

            $this->orm->support_user_access()->where('id_support_user', $idSupportUser)->update(array(
                'maintain_user'  => $accessMaintainUser,
                'support_status' => $accessSupportStatus
            ));

            $this->orm->transaction = 'commit';

            print '{ "ok" : true }';
        }
        catch( Exception $e )
        {
            print '{ "ok" : false, "msg" : "Error occurred" }';
        }
    }

    public function post_list()
    {
        try
        {
            $name      = $this->request->post('name');
            $email     = $this->request->post('email');
            $activeYes = $this->request->post('active_yes', FILTER_VALIDATE_BOOLEAN);
            $activeNo  = $this->request->post('active_no', FILTER_VALIDATE_BOOLEAN);
            $onlineYes = $this->request->post('online_yes', FILTER_VALIDATE_BOOLEAN);
            $onlineNo  = $this->request->post('online_no', FILTER_VALIDATE_BOOLEAN);

            $name  = str_replace(array('%', '_'), array('\%', '\_'), $name);
            $email = str_replace(array('%', '_'), array('\%', '\_'), $email);

            $supportUser = $this->orm->support_user();
            $supportUser->select('id_support_user, name, sex, email');
            $supportUser->select('online, active, last_activity');
            $supportUser->where('name LIKE ?',  "%{$name}%");
            $supportUser->and('email LIKE ?', "%{$email}%");

            if( $activeYes xor $activeNo )
            {
                $supportUser->and('active', $activeYes ? 1 : 0);
            }

            if( $onlineYes xor $onlineNo )
            {
                $supportUser->and('online', $onlineYes ? 1 : 0);
            }

            $dataTimeFormat = new DateTimeFormat();
            $dataTimeFormat->setSupHtmlSuffix(true);

            $users = array();

            foreach( $supportUser as $row )
            {
                $dataTimeFormat->setValue($row['last_activity']);

                $users[] = array(
                    'id_support_user' => $row['id_support_user'],
                    'name'            => $row['name'],
                    'sex'             => str_replace(array('M', 'F'), array('male', 'female'), $row['sex']),
                    'email'           => $row['email'],
                    'online'          => $row['online'],
                    'active'          => $row['active'],
                    'last_activity'   => $dataTimeFormat->format()
                );
            }

            $view = View::instance();
            $view->users = $users;
            $view->render('partial/users-list');
        }
        catch( Exception $e )
        {
            print '
                <tr>
                    <td colspan="7">
                        <div class="result-error">Error occurred</div>
                    </td>
                </tr>
            ';
        }
    }

}
?>