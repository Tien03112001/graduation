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

  static setUser(user: any) {
    StorageUtil.set('token', user['token']);
    StorageUtil.set('name', user['name']);
  }

  static getUser() {
    let user: any = {};
    user.username = StorageUtil.get('username');
    user.photoUrl = StorageUtil.get('photoUrl');
    user.name = StorageUtil.get('name');
    user.token = StorageUtil.get('token');
    return user;
  }


}
