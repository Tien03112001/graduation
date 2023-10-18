import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {AbstractCRUDService} from '../../core/crud';
import {ToasterService} from 'angular2-toaster';
import {TitleService} from '../../core/services';
import {PostCategoryMeta} from './post-category.meta';
import {DataResponse} from '../../core/common';
import {catchError, map} from 'rxjs/operators';
import {Observable} from 'rxjs/Observable';

@Injectable()
export class PostCategoryService extends AbstractCRUDService<PostCategoryMeta> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'Danh mục bài viết', 'post_categories');
  }

  changeOrder(item): any {
    return this.toPipe(this.http.post<DataResponse<any>>(`${this.urlRestAPI}/${item['id']}/change_order`, item));
  }

  up(id: number): Observable<PostCategoryMeta> {
    return this.http.post<DataResponse<PostCategoryMeta>>(`${this.urlRestAPI}/${id}/up`, {})
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }

  down(id: number): Observable<PostCategoryMeta> {
    return this.http.post<DataResponse<PostCategoryMeta>>(`${this.urlRestAPI}/${id}/down`, {})
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }
}
