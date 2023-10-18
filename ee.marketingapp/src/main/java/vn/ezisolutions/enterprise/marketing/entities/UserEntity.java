package vn.ezisolutions.enterprise.marketing.entities;

import vn.ezisolutions.enterprise.marketing.core.BaseEntity;
import lombok.*;
import org.springframework.security.crypto.bcrypt.BCryptPasswordEncoder;

import javax.persistence.*;

@Builder
@NoArgsConstructor
@AllArgsConstructor
@Getter
@Setter
@Entity
@Table(name = "users")
@EqualsAndHashCode(callSuper = true)
public class UserEntity extends BaseEntity {
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    @Id
    private Long id;

    @Column(name = "role_id")
    private Long roleId;

    @Column(name = "store_id")
    private Long storeId;

    @Column(name = "brand_id")
    private Long brandId;
    private String username;
    private String password;
    private String rememberToken;
    private String name;
    private String email;
    private String phone;

    public void setData(UserEntity requestEntity) {
        this.roleId = requestEntity.getRoleId();
        this.brandId = requestEntity.getBrandId();
        this.storeId = requestEntity.getStoreId();
        BCryptPasswordEncoder b = new BCryptPasswordEncoder();
        this.username = requestEntity.getUsername();
        this.password = b.encode(requestEntity.getPassword());
        this.name = requestEntity.getName();
        this.email = requestEntity.getEmail();
        this.phone = requestEntity.getPhone();
    }

}
