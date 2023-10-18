import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {AbstractCRUDService} from '../../core/crud';
import {ToasterService} from 'angular2-toaster';
import {TitleService} from '../../core/services';
import {MenuMeta} from './menu.meta';
import {Observable} from 'rxjs/Observable';
import {DataResponse} from '../../core/common';
import {catchError, map} from 'rxjs/operators';

@Injectable()
export class MenuService extends AbstractCRUDService<MenuMeta> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'Menu', 'menus');
  }

  loadMenuSupport(): Observable<any> {
    return this.toPipe(this.http.get<DataResponse<any>>('menu_supports'));
  }

  up(id: number): Observable<MenuMeta> {
    return this.http.post<DataResponse<MenuMeta>>(`${this.urlRestAPI}/${id}/up`, {})
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }

  down(id: number): Observable<MenuMeta> {
    return this.http.post<DataResponse<MenuMeta>>(`${this.urlRestAPI}/${id}/down`, {})
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }
}
