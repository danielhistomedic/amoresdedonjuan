<?php

/**
 * Clase básica para adminsitrar sesiones
 */
class Session
{

    /**
     * Método Constructor de Controlador Inicio.
     * Inicializa Controllers::__construct.
     * Inicializa y valida datos de session.
     */
    public function __construct()
    {
        $this->init();
    }


    /**
     * Inicializa la sesión
     */
    public function init()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_set_cookie_params(60 * 60 * 12);
            @session_start();
        }
    }

    /**
     * Agrega un elemento a la sesión
     * @param string $key la llave del array de sesión
     * @param string $value el valor para el elemento de la sesión
     */
    public function add($key, $value)
    {
        $key = PREFIJO_SESSION . $key;
        $_SESSION[$key] = $value;
    }

    /**
     * Retorna un elemento a la sesión
     * @param string $key la llave del array de sesión
     * @return string el valor del array de sesión si tiene valor
     */
    public function get($key, $key2 = "", $key3 = "", $key4 = "")
    {
        $key = PREFIJO_SESSION . $key;

        if ($key2 == '' && $key3 == '' && $key4 == '') {
            return !empty($_SESSION[$key]) ? $_SESSION[$key] : null;
        } else if ($key2 != '' && $key3 == '' && $key4 == '') {
            return !empty($_SESSION[$key][$key2]) ? $_SESSION[$key][$key2] : null;
        } else if ($key2 != '' && $key3 != '' && $key4 == '') {
            return !empty($_SESSION[$key][$key2][$key3]) ? $_SESSION[$key][$key2][$key3] : null;
        } else if ($key2 != '' && $key3 != '' && $key4 != '') {
            return !empty($_SESSION[$key][$key2][$key3][$key4]) ? $_SESSION[$key][$key2][$key3][$key4] : null;
        }
    }

    /**
     * Retorna todos los valores del array de sesión
     * @return el array de sesión completo
     */
    public function getAll()
    {
        return $_SESSION;
    }

    /**
     * Remueve un elemento de la sesión
     * @param string $key la llave del array de sesión
     */
    public function remove($key)
    {
        $key = PREFIJO_SESSION . $key;
        if (!empty($_SESSION[$key]))
            unset($_SESSION[$key]);
    }

    /**
     * Cierra la sesión eliminando los valores
     */
    public function close()
    {
        session_unset();
        session_destroy();

        $this->redirect('login');
    }

    /**
     * Retorna el estatus de la sesión
     * @return bool el estatus de la sesión
     */
    public function getStatus(): bool
    {

        $result = true;

        $stat = session_status();

        if ($stat == PHP_SESSION_DISABLED) {
            $result = false;
        } else if ($stat == PHP_SESSION_NONE) {
            $result = false;
        } else if ($stat == PHP_SESSION_ACTIVE) {
            $result = true;
        }

        return $result;
    }


    /**
     * Redirecciona a la pagina especificada.
     * @param $page redirecciona a la pagina indicada
     */
    public function redirect($page)
    {
        header('Location: ' . base_url() . '/' . $page);
        exit();
    }

    /**
     * Establece una cookie
     * @param string $key la llave del array de cookie
     * @param string $value el valor para el elemento de la sesión
     */
    public function setCookie($key, $value)
    {
        $key = PREFIJO_SESSION . $key;
        setcookie($key, $value, time() + 60 * 60 * 7);
    }
}
