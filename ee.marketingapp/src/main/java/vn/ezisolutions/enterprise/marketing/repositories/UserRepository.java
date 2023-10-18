package vn.ezisolutions.enterprise.marketing.repositories;

import vn.ezisolutions.enterprise.marketing.entities.UserEntity;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.data.jpa.repository.JpaSpecificationExecutor;
import org.springframework.data.jpa.repository.Query;
import org.springframework.stereotype.Repository;

import java.util.Optional;

@Repository
public interface UserRepository extends JpaRepository<UserEntity, Long>, JpaSpecificationExecutor<UserEntity> {

    @Query(value = "SELECT u FROM UserEntity u where u.username = ?1")
    Optional<UserEntity> login(String username);

    Optional<UserEntity> findByRememberToken(String token);

}
