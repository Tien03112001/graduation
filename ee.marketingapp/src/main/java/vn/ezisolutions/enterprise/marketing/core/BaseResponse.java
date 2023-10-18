package vn.ezisolutions.enterprise.marketing.core;

import vn.ezisolutions.enterprise.marketing.core.pagination.BasePagination;
import lombok.AllArgsConstructor;
import lombok.Getter;
import lombok.Setter;
import org.springframework.data.domain.Page;

import java.io.Serializable;
import java.util.List;

@Getter
@Setter

@AllArgsConstructor
public class BaseResponse<T> implements Serializable {
    private int status;
    private String message;
    private Object data;

    public BaseResponse() {
        this.status = 200;
        this.message = "success";
        this.data = "đã xóa thành công";
    }

    public BaseResponse(T data) {
        this.status = 200;
        this.message = "success";
        this.data = data;
    }

    public BaseResponse(List<T> data) {
        this.status = 200;
        this.message = "success";
        this.data = data;
    }

    public BaseResponse(Page<T> data) {
        this.status = 200;
        this.message = "success";
        this.data = new BasePagination<T>(data);
    }
}
