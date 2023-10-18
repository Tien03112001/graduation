package vn.ezisolutions.enterprise.marketing.core;

import lombok.*;

import java.util.List;

@Data
@Getter
@Setter
@NoArgsConstructor
@AllArgsConstructor
public class Filter {
    private String field;
    private QueryOperator operator;
    private String value;
    private List<String> values;
}
