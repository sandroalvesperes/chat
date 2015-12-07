<?php
/**
 * Start controller.
 * This is the default controller
 *
 * @author Sandro Alves Peres <sandroalvesperes@yahoo.com.br>
 * @see http://www.zend.com/en/yellow-pages/ZEND022656
 */
class Start extends BaseController
{

    public function get_index()
    {
        $view = View::instance();

        try
        {
            if( isset($_SESSION['client_user']) )
            {
                $chat = $this->orm->chat[ $_SESSION['client_user']['id_chat'] ];

                if( $chat['closed'] )
                {
                    unset($_SESSION['client_user']);
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

            $param = $this->orm->param();
            $param->select('value');
            $param->where('name', 'STATUS');

            $supportStatus = $param->fetch();

            $supportUser = $this->orm->support_user();
            $supportUser->where('active', 1);
            $supportUser->and('online', 1);

            $supportOnlineCount = $supportUser->count('id_support_user');

            if( $supportStatus['value'] == 1 && $supportOnlineCount > 0 )
            {
                $view->render('form-client');
            }
            else
            {
                $view->render('offline');
            }
        }
        catch( Exception $e )
        {
            $view->render('offline');
        }
    }

    public function all_notFound()
    {
        $view = View::instance();
        $view->render('not-found');
    }

}
?>