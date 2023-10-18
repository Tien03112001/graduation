package vn.ezisolutions.enterprise.marketing.core;

import org.springframework.http.HttpHeaders;
import org.springframework.http.HttpStatus;
import org.springframework.http.ResponseEntity;
import org.springframework.security.core.userdetails.UsernameNotFoundException;
import org.springframework.security.web.firewall.RequestRejectedException;
import org.springframework.validation.FieldError;
import org.springframework.web.bind.annotation.ControllerAdvice;
import org.springframework.web.bind.annotation.ExceptionHandler;
import org.springframework.web.context.request.WebRequest;
import org.springframework.web.servlet.mvc.method.annotation.ResponseEntityExceptionHandler;
import vn.ezisolutions.enterprise.marketing.exceptions.CustomException;
import vn.ezisolutions.enterprise.marketing.exceptions.CustomValidationException;

import java.util.HashMap;
import java.util.Map;

@ControllerAdvice
public class RestResponseEntityExceptionHandler extends ResponseEntityExceptionHandler {

    @ExceptionHandler({Exception.class})
    public ResponseEntity<Object> handleAccessDeniedException(
            Exception ex, WebRequest request) {
        return new ResponseEntity<>(
                new BaseResponse<String>(500, ex.getMessage(), null), new HttpHeaders(), HttpStatus.FORBIDDEN);
    }

    @ExceptionHandler({UsernameNotFoundException.class})
    public ResponseEntity<Object> handleUsernameNotFoundExceptionException(
            Exception ex, WebRequest request) {
        return new ResponseEntity<>(
                new BaseResponse<String>(401, ex.getMessage(), null), new HttpHeaders(), HttpStatus.UNAUTHORIZED);
    }

    @ExceptionHandler({CustomException.class})
    public ResponseEntity<Object> handleAccessDeniedException(
            CustomException ex, WebRequest request) {

        return new ResponseEntity<>(
                new BaseResponse<String>(ex.getStatusCode(), ex.getMessage(), null), new HttpHeaders(), HttpStatus.valueOf(ex.getStatusCode()));
    }

    @ExceptionHandler({CustomValidationException.class})
    public ResponseEntity<Object> handleValidationException(
            CustomValidationException ex, WebRequest request) {
        Map<String, String> errors = new HashMap<>();
        ex.getErrors().forEach((error) -> {
            String fieldName = ((FieldError) error).getField();
            String errorMessage = error.getDefaultMessage();
            errors.put(fieldName, errorMessage);
        });
        return new ResponseEntity<>(
                new BaseErrorResponse<>(400, ex.getMessage(), errors), new HttpHeaders(), HttpStatus.BAD_REQUEST);
    }

    @ExceptionHandler({RequestRejectedException.class})
    public ResponseEntity<Object> handleRequestRejectedException(
            CustomValidationException ex, WebRequest request) {
        return new ResponseEntity<>(
                new BaseErrorResponse<>(405, ex.getMessage(), null), new HttpHeaders(), HttpStatus.METHOD_NOT_ALLOWED);
    }
}