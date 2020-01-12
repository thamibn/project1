<?php


namespace App\Traits;


use Exception;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

trait ApiExceptionHandler
{
    function apiException(Request $request, Exception $exception)
    {
        if ($exception instanceof ModelNotFoundException) {
            $errorBody = [
                'status' => Response::HTTP_NOT_FOUND,
                'code' => $exception->getCode(),
                'track' => 'MODEL_404',
                'message' => ucfirst(str_replace('App\\Models\\', '', $exception->getModel()))." not found",
            ];
        } else if ($exception instanceof NotFoundHttpException)
            $errorBody = [
                'status' => Response::HTTP_NOT_FOUND,
                'code' => $exception->getCode(),
                'track' => 'ROUTE_404',
                'message' => 'The requested route was not found',
            ];
        else if ($exception instanceof MethodNotAllowedHttpException)
            $errorBody = [
                'status' => Response::HTTP_METHOD_NOT_ALLOWED,
                'code' => $exception->getCode(),
                'track' => 'METHOD_405',
                'message' => $exception->getMessage(),
            ];
        else if ($exception instanceof ValidationException)
            $errorBody = [
                'status' => $exception->status,
                'code' => $exception->getCode(),
                'track' => ($exception->status === 422) ? "VALIDATION_422" : "ATTEMPTS_429",
                'message' => $exception->errors(),
            ];
        else if ($exception instanceof BadRequestHttpException)
            $errorBody = [
                'status' => Response::HTTP_BAD_REQUEST,
                'code' => $exception->getCode(),
                'track' => 'REQUEST_400',
                'message' => $exception->getMessage(),
            ];
        else if ($exception instanceof AuthenticationException)
            $errorBody = [
                'status' => Response::HTTP_UNAUTHORIZED,
                'code' => $exception->getCode(),
                'track' => 'AUTHENTICATION_401',
                'message' => "Unauthenticated, Please login.",
            ];
        else if ($exception instanceof AuthorizationException)
            $errorBody = [
                'status' => Response::HTTP_FORBIDDEN,
                'code' => $exception->getCode(),
                'track' => 'AUTHORIZATION_403',
                'message' => $exception->getMessage(),
            ];
        else if($exception instanceof  HttpException){
            $errorBody = [
                'status' => $exception->getStatusCode(),
                'code' => $exception->getCode(),
                'track' => 'HTTP_'.$exception->getCode(),
                'message' => $exception->getMessage(),
            ];
        }
        else if($exception instanceof  QueryException){
            $errorCode = $exception->errorInfo[1];
            if($errorCode == 1451) {
                $errorBody = [
                    'status' => Response::HTTP_CONFLICT,
                    'code' => $exception->getCode(),
                    'track' => 'SQLSTATE['.$exception->getCode().']',
                    'message' => "Cannot remove resource, it is related to another resource",
                ];
            }else if($errorCode === 1452){
                $errorBody = [
                    'status' => Response::HTTP_CONFLICT,
                    'code' => $exception->getCode(),
                    'track' => 'SQLSTATE['.$exception->getCode().']',
                    'message' => "Cannot add or update a child row: a foreign key constraint fails",
                ];
            }
        }
        else{
            $errorBody = [
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'code' => $exception->getCode(),
                'track' => 'SERVER_500',
                'message' => $exception->getMessage(),
            ];
        }

        return \response()->json([
            'error' => $errorBody,
            'Exception' => get_class($exception),
            'file' => $exception->getFile(),
            'Line' => $exception->getLine()
        ], $errorBody['status']);
    }
}
