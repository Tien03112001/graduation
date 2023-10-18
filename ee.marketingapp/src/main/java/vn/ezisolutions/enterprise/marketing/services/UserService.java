package vn.ezisolutions.enterprise.marketing.services;

import vn.ezisolutions.enterprise.marketing.modules.login.AuthUser;
import vn.ezisolutions.enterprise.marketing.core.Filter;
import vn.ezisolutions.enterprise.marketing.exceptions.CustomException;
import vn.ezisolutions.enterprise.marketing.entities.UserEntity;
import org.springframework.data.domain.Page;

import java.util.List;
import java.util.Map;
import java.util.Optional;

public interface UserService {

    UserEntity login(String username, String password) throws CustomException;

    Optional<AuthUser> findByToken(String token);

    Page<UserEntity> paginate(Integer page, Integer limit, List<Filter> whereParams, Map<String, String> sortBy) throws CustomException;

}