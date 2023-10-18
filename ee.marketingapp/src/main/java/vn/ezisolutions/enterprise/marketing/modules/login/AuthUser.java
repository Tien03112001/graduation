package vn.ezisolutions.enterprise.marketing.modules.login;

import lombok.Getter;
import lombok.Setter;
import org.springframework.security.core.GrantedAuthority;
import org.springframework.security.core.authority.AuthorityUtils;
import org.springframework.security.core.userdetails.User;
import vn.ezisolutions.enterprise.marketing.entities.RoleEntity;
import vn.ezisolutions.enterprise.marketing.entities.UserEntity;

import java.util.Collection;

@Getter
@Setter
public class AuthUser extends User {

   /* private Long storeId;
    private Long brandId;
    private Long roleId;*/

    public AuthUser(String username, String password, Collection<? extends GrantedAuthority> authorities) {
        super(username, password, authorities);
    }

    public AuthUser(String username, String password, boolean enabled, boolean accountNonExpired, boolean credentialsNonExpired, boolean accountNonLocked, Collection<? extends GrantedAuthority> authorities) {
        super(username, password, enabled, accountNonExpired, credentialsNonExpired, accountNonLocked, authorities);
    }

    public AuthUser(UserEntity userEntity, RoleEntity roleEntity) {
        this(userEntity.getUsername(), userEntity.getPassword(), true, true, true, true,
                AuthorityUtils.createAuthorityList(roleEntity.getCode()));
    }
}
