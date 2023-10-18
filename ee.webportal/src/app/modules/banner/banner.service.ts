import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {AbstractCRUDService} from '../../core/crud';
import {ToasterService} from 'angular2-toaster';
import {TitleService} from '../../core/services';
import {BannerMeta} from './banner.meta';
import {Observable} from 'rxjs/Observable';
import {DataResponse} from '../../core/common';
import {catchError, map} from 'rxjs/operators';

@Injectable()
export class BannerService extends AbstractCRUDService<BannerMeta> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'Banner', 'banners');
  }

  updatePriority(object: BannerMeta): Observable<DataResponse<BannerMeta>> {
    return this.toPipe(this.http.put<DataResponse<BannerMeta>>(`banners/${object['id']}/priority`, object));
  }

  up(id: number): Observable<BannerMeta> {
    return this.http.post<DataResponse<BannerMeta>>(`${this.urlRestAPI}/${id}/up`, {})
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }

  down(id: number): Observable<BannerMeta> {
    return this.http.post<DataResponse<BannerMeta>>(`${this.urlRestAPI}/${id}/down`, {})
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }
}
