import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import 'rxjs/add/operator/map';
import {Observable} from 'rxjs';
import {UserMeta} from '../user/user.meta';

@Injectable()
export class ProfileService {

  constructor(private http: HttpClient) {
  }

  get(): Observable<UserMeta> {
    return this.http.get<any>('profile').map(res=>res.data);
  }

  update(user: any): Observable<any> {
    return this.http.post<any>('profile', user);
  }

}
