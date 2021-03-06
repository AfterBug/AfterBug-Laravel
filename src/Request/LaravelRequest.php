<?php

namespace AfterBug\AfterBugLaravel\Request;

use Illuminate\Http\Request;
use AfterBug\Request\Contracts\RequestInterface;

class LaravelRequest implements RequestInterface
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * LaravelRequest constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Get the session data.
     *
     * @return array
     */
    public function getSession()
    {
        $session = $this->request->getSession();

        return $session ? $session->all() : [];
    }

    /**
     * Get the cookies.
     *
     * @return array
     */
    public function getCookies()
    {
        return $this->request->cookies->all();
    }

    /**
     * Get the headers.
     *
     * @return array
     */
    public function getHeaders()
    {
        return $this->request->headers->all();
    }

    /**
     * Get server variable.
     *
     * @return array
     */
    public function getServer()
    {
        return (array) $this->request->server->getIterator();
    }

    /**
     * Get the request formatted as meta data.
     *
     * @return array
     */
    public function getMetaData()
    {
        $data = [];

        $data['url'] = $this->request->fullUrl();
        $data['method'] = $this->request->getMethod();

        $params = $this->request->input();

        foreach (config()->get('afterbug.blacklist') as $blacklist) {
            if (array_key_exists($blacklist, $params)) {
                $params[$blacklist] = '******';
            }
        }

        $data['params'] = $params;

        $data['clientIp'] = $this->request->getClientIp();

        if ($agent = $this->request->header('User-Agent')) {
            $data['userAgent'] = $agent;
        }

        return $data;
    }

    /**
     * Get the request ip.
     *
     * @return string|null
     */
    public function getRequestIp()
    {
        return $this->request->getClientIp();
    }
}
