package vn.ezisolutions.enterprise.marketing.modules.login;

import vn.ezisolutions.enterprise.marketing.entities.UserEntity;

public class AuthMapper {

    private static AuthMapper INSTANCE;

    public static AuthMapper getInstance() {
        if (INSTANCE == null) {
            INSTANCE = new AuthMapper();
        }

        return INSTANCE;
    }

    public AuthDTO toDTO(UserEntity user) {
        return new AuthDTO(user.getRememberToken(), user.getName());
    }
}