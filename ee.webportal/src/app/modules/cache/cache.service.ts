import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {AbstractCRUDService} from '../../core/crud';
import {ToasterService} from 'angular2-toaster';
import {TitleService} from '../../core/services';
import {CacheMeta} from './cache.meta';
import {Observable} from 'rxjs/Observable';
import {BannerMeta} from '../banner/banner.meta';
import {DataResponse} from '../../core/common';
import {catchError, map} from 'rxjs/operators';

@Injectable()
export class CacheService extends AbstractCRUDService<CacheMeta> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'Cache', 'cache');
  }
  clear() {
    return this.http.post(`${this.urlRestAPI}/clear_cache_page`, {})
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }
}
