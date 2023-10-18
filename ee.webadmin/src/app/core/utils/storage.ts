import {Auth} from '../../modules/auth';

export class StorageUtil {

  static get(key: string): string {
    return window.localStorage.getItem(key);
  }

  static set(key: string, value: string): void {
    window.localStorage.setItem(key, value);
  }

  static delete(key: string): void {
    window.localStorage.removeItem(key);
  }

  static clear(): void {
    window.localStorage.clear();
  }

  static setUser(user: Auth) {
    StorageUtil.set('name', user.name);
    StorageUtil.set('token', user.authToken);
    StorageUtil.set('photoUrl', '/assets/avatar5.png');
  }

}
