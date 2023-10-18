package vn.ezisolutions.enterprise.marketing.modules.login;

import vn.ezisolutions.enterprise.marketing.core.BaseResponse;

public class AuthResponse extends BaseResponse<AuthDTO> {
    public AuthResponse(int status, String message, AuthDTO data) {
        super(status, message, data);
    }

    public AuthResponse() {
    }

    public AuthResponse(AuthDTO data) {
        super(data);
    }
}
