package vn.ezisolutions.enterprise.marketing.core;

import org.springframework.security.web.firewall.RequestRejectedException;
import org.springframework.security.web.firewall.RequestRejectedHandler;
import org.springframework.web.bind.annotation.ControllerAdvice;

import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import java.io.IOException;

@ControllerAdvice
public class RequestRejectedExceptionHandler implements RequestRejectedHandler {

    @Override
    public void handle(HttpServletRequest request, HttpServletResponse response, RequestRejectedException requestRejectedException) throws IOException {
        response.sendError(HttpServletResponse.SC_METHOD_NOT_ALLOWED);
    }
}