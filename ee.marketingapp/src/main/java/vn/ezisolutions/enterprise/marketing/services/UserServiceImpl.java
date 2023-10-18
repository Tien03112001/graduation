package vn.ezisolutions.enterprise.marketing.services;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.data.domain.Page;
import org.springframework.data.domain.PageRequest;
import org.springframework.data.domain.Pageable;
import org.springframework.data.domain.Sort;
import org.springframework.data.jpa.domain.Specification;
import org.springframework.security.crypto.bcrypt.BCryptPasswordEncoder;
import org.springframework.stereotype.Service;
import org.springframework.transaction.annotation.Transactional;
import vn.ezisolutions.enterprise.marketing.core.Filter;
import vn.ezisolutions.enterprise.marketing.entities.RoleEntity;
import vn.ezisolutions.enterprise.marketing.entities.UserEntity;
import vn.ezisolutions.enterprise.marketing.exceptions.CustomException;
import vn.ezisolutions.enterprise.marketing.modules.login.AuthUser;
import vn.ezisolutions.enterprise.marketing.repositories.RoleRepository;
import vn.ezisolutions.enterprise.marketing.repositories.UserRepository;
import vn.ezisolutions.enterprise.marketing.specifications.UserSpecifications;

import java.util.ArrayList;
import java.util.List;
import java.util.Map;
import java.util.Optional;

@Service("userService")
@Transactional(rollbackFor = Throwable.class)
public class UserServiceImpl implements UserService {
    private final String input = "ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890qwertyuiopasdfghjklzxcvbnm";

    @Autowired
    UserRepository userRepository;
    @Autowired
    RoleRepository roleRepository;

    @Override
    public UserEntity login(String username, String password) throws CustomException {
        if (username == null || "".equals(username.trim())) {
            throw new CustomException(403, "không được bỏ trống user");
        } else if (password == null || "".equals(password.trim())) {
            throw new CustomException(403, "không được bỏ trống password");
        }
        Optional<UserEntity> userEntity = userRepository.login(username);
        if (userEntity.isPresent()) {
            UserEntity user = userEntity.get();
            BCryptPasswordEncoder b = new BCryptPasswordEncoder();
            if (b.matches(password, userEntity.get().getPassword())) {
                return user;
            }
        }

        return null;
    }

    @Override
    public Optional<AuthUser> findByToken(String token) {
        Optional<UserEntity> userEntityOptional = userRepository.findByRememberToken(token);
        if (userEntityOptional.isPresent()) {
            UserEntity userEntity = userEntityOptional.get();

            Optional<RoleEntity> roleEntityOptional = roleRepository.findById(userEntity.getRoleId());
            if (roleEntityOptional.isEmpty()) {
                return Optional.empty();
            }

            RoleEntity roleEntity = roleEntityOptional.get();

            AuthUser user = new AuthUser(userEntity, roleEntity);

            return Optional.of(user);
        }
        return Optional.empty();
    }

    @Override
    public Page<UserEntity> paginate(Integer page, Integer limit, List<Filter> filters, Map<String, String> sortBy) throws CustomException {
        List<Sort.Order> orders = new ArrayList<>();
        for (String field : sortBy.keySet()) {
            orders.add(new Sort.Order(Sort.Direction.fromString(sortBy.get(field)), field));
        }
        Sort sort = orders.size() > 0 ? Sort.by(orders) : Sort.by("id").descending();
        Pageable pageable = PageRequest.of(page, limit, sort);

        Specification<UserEntity> specifications = UserSpecifications.getInstance().getQueryResult(filters);

        return userRepository.findAll(specifications, pageable);
    }
}