import {Injectable} from '@angular/core';
import {HttpClient, HttpHeaders} from '@angular/common/http';
import {AbstractCRUDService} from '../../core/crud';
import {ToasterService} from 'angular2-toaster';
import {TitleService} from '../../core/services/';
import {PhotoMeta} from './photo.meta';
import {Observable} from 'rxjs/Observable';
import {BlockMeta} from '../block/block.meta';
import {DataResponse} from '../../core/common';
import {catchError, map} from 'rxjs/operators';

@Injectable()
export class PhotoService extends AbstractCRUDService<PhotoMeta> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'Ảnh', 'photos');
  }

  storeWithImage(item: any): Observable<any> {
    const formData = new FormData();
    Object.keys(item).forEach(key => formData.append(key, item[key]));
    return this.http.post(`photos`, formData, {headers: new HttpHeaders({uploadFile: 'true'})});
  }

  updateWithImage(item: any): Observable<any> {
    const formData = new FormData();
    Object.keys(item).forEach(key => formData.append(key, item[key]));
    return this.http.post(`photos/${item.id}`, formData, {headers: new HttpHeaders({uploadFile: 'true'})});
  }

  up(id: number): Observable<PhotoMeta> {
    return this.http.post<DataResponse<PhotoMeta>>(`${this.urlRestAPI}/${id}/up`, {})
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }

  down(id: number): Observable<PhotoMeta> {
    return this.http.post<DataResponse<PhotoMeta>>(`${this.urlRestAPI}/${id}/down`, {})
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }
}
