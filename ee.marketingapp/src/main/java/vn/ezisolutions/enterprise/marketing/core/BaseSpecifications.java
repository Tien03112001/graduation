package vn.ezisolutions.enterprise.marketing.core;

import org.springframework.data.jpa.domain.Specification;

import java.math.BigDecimal;
import java.util.ArrayList;
import java.util.List;

public class BaseSpecifications<T> {

    private Specification<T> createSpecification(Filter input) {

        return switch (input.getOperator()) {
            case EQUALS -> (root, query, criteriaBuilder) ->
                    criteriaBuilder.equal(root.get(input.getField()),
                            castToRequiredType(root.get(input.getField()).getJavaType(), input.getValue()));
            case NOT_EQ -> (root, query, criteriaBuilder) ->
                    criteriaBuilder.notEqual(root.get(input.getField()),
                            castToRequiredType(root.get(input.getField()).getJavaType(), input.getValue()));
            case GREATER_THAN -> (root, query, criteriaBuilder) ->
                    criteriaBuilder.gt(root.get(input.getField()),
                            (Number) castToRequiredType(root.get(input.getField()).getJavaType(), input.getValue()));
            case LESS_THAN -> (root, query, criteriaBuilder) ->
                    criteriaBuilder.lt(root.get(input.getField()),
                            (Number) castToRequiredType(root.get(input.getField()).getJavaType(), input.getValue()));
            case LIKE -> (root, query, criteriaBuilder) ->
                    criteriaBuilder.like(root.get(input.getField()), "%" + input.getValue() + "%");
            case IN -> (root, query, criteriaBuilder) ->
                    criteriaBuilder.in(root.get(input.getField()))
                            .value(castToRequiredType(root.get(input.getField()).getJavaType(), input.getValues()));
        };
    }

    public Specification<T> getQueryResult(List<Filter> filters) {
        if (filters != null && filters.size() > 0) {
            return getSpecificationFromFilters(filters);
        } else {
            return Specification.where(null);
        }
    }

    private Specification<T> getSpecificationFromFilters(List<Filter> filter) {
        Specification<T> specification = Specification.where(null);
        for (Filter input : filter) {
            specification = specification.and(createSpecification(input));
        }
        return specification;
    }

    public Specification<T> exactIgnoreCase(String property, String term) {
        return (root, query, cb) -> {
            return cb.equal(cb.lower(root.get(property)), term);
        };
    }

    public Specification<T> containsIgnoreCase(String property, String searchTerm) {
        return (root, query, cb) -> {
            String containsLikePattern = getContainsLikePattern(searchTerm);
            return cb.like(cb.lower(root.get(property)), containsLikePattern);
        };
    }

    private static String getContainsLikePattern(String searchTerm) {
        if (searchTerm == null || searchTerm.isEmpty()) {
            return "%";
        } else {
            return "%" + searchTerm.toLowerCase() + "%";
        }
    }

    private Object castToRequiredType(Class fieldType, String value) {
        if (fieldType.isAssignableFrom(Double.class)) {
            return Double.valueOf(value);
        } else if (fieldType.isAssignableFrom(Integer.class)) {
            return Integer.valueOf(value);
        } else if (fieldType.isAssignableFrom(Long.class)) {
            return Long.valueOf(value);
        } else if (fieldType.isAssignableFrom(BigDecimal.class)) {
            return new BigDecimal(value);
        } else if (Enum.class.isAssignableFrom(fieldType)) {
            return Enum.valueOf(fieldType, value);
        }
        return value;
    }

    private Object castToRequiredType(Class fieldType, List<String> value) {
        List<Object> lists = new ArrayList<>();
        for (String s : value) {
            lists.add(castToRequiredType(fieldType, s));
        }
        return lists;
    }
}
