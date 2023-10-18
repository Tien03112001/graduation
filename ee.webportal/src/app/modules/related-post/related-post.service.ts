import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {AbstractCRUDService} from '../../core/crud';
import {ToasterService} from 'angular2-toaster';
import {TitleService} from '../../core/services';
import {RelatedPostMeta} from './related-post.meta';
import {Observable} from 'rxjs/Observable';
import {DataResponse} from '../../core/common';
import {catchError, map} from 'rxjs/operators';

@Injectable()
export class RelatedPostService extends AbstractCRUDService<RelatedPostMeta> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'Related Post', 'related_posts');
  }

  up(id: number): Observable<RelatedPostMeta> {
    return this.http.post<DataResponse<RelatedPostMeta>>(`${this.urlRestAPI}/${id}/up`, {})
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }

  down(id: number): Observable<RelatedPostMeta> {
    return this.http.post<DataResponse<RelatedPostMeta>>(`${this.urlRestAPI}/${id}/down`, {})
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }

}
