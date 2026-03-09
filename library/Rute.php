<?php
namespace muhammad\routing;

class Rute {
    protected static $rute = [];
    protected static $middlewareGrup = [];

    public static function middleware($middleware) {
        self::$middlewareGrup[] = $middleware;
        return new static;
    }

    public static function grup($callback) {
        $callback();
        array_pop(self::$middlewareGrup);
    }

    public static function ambil($uri, $callback) {
        self::tambahRute('GET', $uri, $callback);
    }

    public static function kirim($uri, $callback) {
        self::tambahRute('POST', $uri, $callback);
    }

    public static function dapatkan($uri, $callback) {
        self::ambil($uri, $callback);
    }

    public static function tambah($uri, $callback) {
        self::kirim($uri, $callback);
    }

    protected static function tambahRute($method, $uri, $callback) {
        $uri = trim($uri, '/');
        if ($uri === '') $uri = '/';
        self::$rute[] = [
            'method' => $method,
            'uri' => $uri,
            'callback' => $callback,
            'middleware' => self::$middlewareGrup
        ];
    }

    public static function jalankan($uri_saat_ini, $method_saat_ini) {
        $uri_saat_ini = trim($uri_saat_ini, '/');
        if ($uri_saat_ini === '') $uri_saat_ini = '/';

        foreach (self::$rute as $r) {
            if ($r['method'] === $method_saat_ini) {
                // Buat pola regex untuk parameter rute
                $pola = preg_replace('/\{[a-zA-Z0-9_]+\}/', '([a-zA-Z0-9_-]+)', $r['uri']);
                $pola = str_replace('/', '\/', $pola);
                $pola = '/^' . $pola . '$/';

                if (preg_match($pola, $uri_saat_ini, $matches)) {
                    array_shift($matches);

                    // Proses middleware
                    return self::jalankanMiddleware($r['middleware'], function() use ($r, $matches) {
                        return self::panggilCallback($r['callback'], $matches);
                    });
                }
            }
        }

        // Jika rute tidak ditemukan
        if (function_exists('customErrorHandler')) {
            customErrorHandler();
        } else {
            header("HTTP/1.0 404 Not Found");
            echo "404 Halaman Tidak Ditemukan";
        }
        exit();
    }

    protected static function panggilCallback($callback, $params = []) {
        if (is_callable($callback)) {
            return call_user_func_array($callback, $params);
        }

        if (is_string($callback)) {
            // Format: 'Controller@method'
            if (strpos($callback, '@') !== false) {
                list($controller, $method) = explode('@', $callback);
                if (class_exists($controller)) {
                    $instance = new $controller();
                    return call_user_func_array([$instance, $method], $params);
                }
            }
            
            // Jika callback adalah string (view name)
            if (function_exists('view')) {
                require_once view($callback, $params);
                return;
            }
        }

        if (is_array($callback)) {
            // Format: [Controller::class, 'method']
            $controller = $callback[0];
            $method = $callback[1];
            if (class_exists($controller)) {
                $instance = new $controller();
                return call_user_func_array([$instance, $method], $params);
            }
        }

        throw new \Exception("Callback rute tidak valid.");
    }

    protected static function jalankanMiddleware($middlewares, $next) {
        if (empty($middlewares)) {
            return $next();
        }

        $middleware = array_shift($middlewares);

        list($class, $parameter) = array_pad(explode(':', $middleware), 2, null);

        $middlewareClass = "app\\Http\\Middleware\\" . $class;

        if (!class_exists($middlewareClass)) {
            throw new \Exception("Middleware {$middlewareClass} tidak ditemukan.");
        }

        $instance = new $middlewareClass();

        return $instance->tangani(function() use ($middlewares, $next) {
            return self::jalankanMiddleware($middlewares, $next);
        }, $parameter);
    }

    // Alias for Laravel users
    public static function get($uri, $callback) { self::ambil($uri, $callback); }
    public static function post($uri, $callback) { self::kirim($uri, $callback); }
    public static function dispatch($uri, $method) { self::jalankan($uri, $method); }
}
