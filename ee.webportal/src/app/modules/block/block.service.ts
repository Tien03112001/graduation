import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {AbstractCRUDService} from '../../core/crud';
import {ToasterService} from 'angular2-toaster';
import {TitleService} from '../../core/services';
import {BlockMeta} from './block.meta';
import {Observable} from 'rxjs/Observable';
import {DataResponse} from '../../core/common';
import {catchError, map} from 'rxjs/operators';

@Injectable()
export class BlockService extends AbstractCRUDService<BlockMeta> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'Block', 'blocks');
  }

  up(id: number): Observable<BlockMeta> {
    return this.http.post<DataResponse<BlockMeta>>(`${this.urlRestAPI}/${id}/up`, {})
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }

  down(id: number): Observable<BlockMeta> {
    return this.http.post<DataResponse<BlockMeta>>(`${this.urlRestAPI}/${id}/down`, {})
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }
}
