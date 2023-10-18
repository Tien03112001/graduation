package vn.ezisolutions.enterprise.marketing.repositories;

import vn.ezisolutions.enterprise.marketing.entities.RoleEntity;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.data.jpa.repository.JpaSpecificationExecutor;
import org.springframework.stereotype.Repository;

@Repository
public interface RoleRepository extends JpaRepository<RoleEntity, Long>, JpaSpecificationExecutor<RoleEntity> {
    RoleEntity findByName(String id);

}
