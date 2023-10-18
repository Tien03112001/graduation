package vn.ezisolutions.enterprise.marketing.utils;

import org.springframework.security.core.Authentication;
import org.springframework.security.core.context.SecurityContextHolder;
import vn.ezisolutions.enterprise.marketing.modules.login.AuthUser;

public class AuthUtil {

    private final AuthUser authUser;

    private static AuthUtil INSTANCE;

    public static AuthUtil getInstance() {
        if (INSTANCE == null) {
            INSTANCE = new AuthUtil();
        }
        return INSTANCE;
    }

    public AuthUtil() {
        Authentication authentication = SecurityContextHolder.getContext().getAuthentication();
        authUser = (AuthUser) authentication.getPrincipal();
    }

    public AuthUser getCurrentUser() {
        return authUser;
    }
}
