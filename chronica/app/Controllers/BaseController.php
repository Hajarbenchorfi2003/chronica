<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var array
     */
    protected $helpers = [];

    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    protected $session;
    protected $validation;
    protected $data = [];

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        $this->session = \Config\Services::session();
        
        // Ajouter les données communes à toutes les vues
        $this->data['user'] = $this->session->get('user');
        $this->data['isLoggedIn'] = $this->session->get('logged_in');
        $this->data['isAdmin'] = $this->session->get('role') === 'admin';

        // Preload any models, libraries, etc, here.
        $this->validation = \Config\Services::validation();
    }

    /**
     * Set validation rules
     *
     * @param array $rules
     * @return void
     */
    protected function setValidationRules($rules)
    {
        $this->validation->setRules($rules);
    }

    /**
     * Validate the request
     *
     * @param array $rules
     * @param array $messages
     * @return bool
     */
    protected function validate($rules, array $messages = []): bool
    {
        if (!$this->validation->withRequest($this->request)->run()) {
            return false;
        }
        return true;
    }

    protected function getValidationErrors()
    {
        return $this->validation->getErrors();
    }

    protected function setFlashMessage($type, $message)
    {
        $this->session->setFlashdata($type, $message);
    }

    protected function redirectWithMessage($url, $type, $message)
    {
        $this->setFlashMessage($type, $message);
        return redirect()->to($url);
    }

    protected function isPost()
    {
        return $this->request->getMethod() === 'post';
    }

    protected function isAjax()
    {
        return $this->request->isAJAX();
    }

    protected function getPost($key = null)
    {
        return $this->request->getPost($key);
    }

    protected function getGet($key = null)
    {
        return $this->request->getGet($key);
    }

    protected function json($data, $status = 200)
    {
        return $this->response->setJSON($data)->setStatusCode($status);
    }
}
