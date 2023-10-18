package vn.ezisolutions.enterprise.marketing.specifications;


import vn.ezisolutions.enterprise.marketing.core.BaseSpecifications;
import vn.ezisolutions.enterprise.marketing.entities.UserEntity;

public class UserSpecifications extends BaseSpecifications<UserEntity> {

    private static UserSpecifications INSTANCE;

    public static UserSpecifications getInstance() {
        if (INSTANCE == null) {
            INSTANCE = new UserSpecifications();
        }

        return INSTANCE;
    }

}
