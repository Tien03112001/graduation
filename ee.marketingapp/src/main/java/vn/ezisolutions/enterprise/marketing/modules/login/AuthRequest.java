package vn.ezisolutions.enterprise.marketing.modules.login;

import lombok.AllArgsConstructor;
import lombok.Getter;
import lombok.NoArgsConstructor;
import lombok.Setter;

import java.io.Serializable;

@Getter
@Setter
@NoArgsConstructor
@AllArgsConstructor
public class AuthRequest implements Serializable {
    private String username;
    private String password;
}
