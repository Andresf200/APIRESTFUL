<?php

namespace App\Exceptions;

use App\Traits\ApiResponse;
use Asm89\Stack\CorsService;
use Fruitcake\Cors\CorsServiceProvider;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    use ApiResponse;
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $e)
    {
        $response = $this->handleException($request,$e);
        app(CorsService::class)->addActualRequestHeaders($response, $request);

        return $response;
    }

    public function handleException($request, Throwable $e){
        if ($e instanceof ValidationException){
            $this->convertValidationExceptionToResponse($e, $request);
        }

        if ($e instanceof ModelNotFoundException){
            $modelo = strtolower(class_basename($e->getModel()));
            return $this->errorResponse("No existe ninguna instancia de {$modelo} con el id especificado", 404);
        }

        if ($e instanceof AuthenticationException){
            return $this->unauthenticated($request, $e);
        }

        if ($e instanceof AuthorizationException){
            return $this->errorResponse('No posee permisos para ejecutar esta acción', 403);
        }

        if ($e instanceof NotFoundHttpException){
            return $this->errorResponse('No se encontro la URL especificada', 404);
        }

        if (method_exists($e, 'render') && $response = $e->render($request)) {
            return Router::toResponse($request, $response);
        } elseif ($e instanceof Responsable) {
            return $e->toResponse($request);
        }

        if ($e instanceof MethodNotAllowedHttpException){
            return $this->errorResponse('El metodo especificado en la peticion no es valido', 404);
        }

        if ($e instanceof HttpException){
            return $this->errorResponse($e->getMessage(), $e->getStatusCode());
        }

        if ($e instanceof QueryException){
            $codigo = $e->errorInfo[1];
            if ($codigo == 7){
                return $this->errorResponse('No se puede eliminar de forma permanente el recurso porque esta relacionado con algun otro.', 409);
            }
        }

        if($e instanceof TokenMismatchException){
            return redirect()->back()->withInput($request->input());
        }

        if (config('app.debug')){
            $e = $this->prepareException($this->mapException($e));

            foreach ($this->renderCallbacks as $renderCallback) {
                if (is_a($e, $this->firstClosureParameterType($renderCallback))) {
                    $response = $renderCallback($e, $request);

                    if (! is_null($response)) {
                        return $response;
                    }
                }
            }

            if ($e instanceof HttpResponseException) {
                return $e->getResponse();
            } elseif ($e instanceof AuthenticationException) {
                return $this->unauthenticated($request, $e);
            } elseif ($e instanceof ValidationException) {
                return $this->convertValidationExceptionToResponse($e, $request);
            }

            return $request->expectsJson()
                ? $this->prepareJsonResponse($request, $e)
                : $this->prepareResponse($request, $e);
        }

        return $this->errorResponse('Falla inesperada. intente luego', 500);

    }

    /**
     * Create a response object from the given validation exception.
     *
     * @param ValidationException $e
     * @param  Request  $request
     * @return Response
     */
    protected function convertValidationExceptionToResponse(ValidationException $e, $request): Response // Validación correspondiente a los campos de login
    {
        $errors = $e->validator->errors()->getMessages();

        if ($this->isFrontend($request)){
            return $request->ajax() ? response()->json($errors, 422): redirect() // validamos que la entrada desde el login sea una petición sincrona o una petición asincrona (AJAX, FECH, HTTpclient)
                ->back()
                ->withInput($request->input()) //retornamos los datos de los inputs para que no se pierdan en el input
                ->withErrors($errors); // retornamos los errores
        }

        return $this->errorResponse($errors, 422);
    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($this->isFrontend($request)){
            return redirect()->guest('login');
        }

        return $this->errorResponse('No autenticado', 401);
    }

    private function isFrontend($request): bool
    {
        return $request->acceptsHtml() && collect($request->route()->middleware())->contains('web'); // Verifica que la petición del cliente venga del front con acepte html y que tenga la ruta el middleware web
    }
}
