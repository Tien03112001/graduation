import {Injectable} from '@angular/core';
import {HttpClient, HttpParams} from '@angular/common/http';
import {AbstractCRUDService} from '../../core/crud';
import {ToasterService} from 'angular2-toaster';
import {TitleService} from '../../core/services';
import {GalleryImageMeta} from './gallery.meta';
import {Observable} from 'rxjs/Observable';
import {DataResponse} from '../../core/common';
import {PhotoMeta} from '../photo-CRUD/photo.meta';

@Injectable()
export class GalleryImageService extends AbstractCRUDService<GalleryImageMeta> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'Album Ảnh', 'gallery_images');
  }

  images(id: number): Observable<PhotoMeta[]> {
    let parameters: HttpParams = new HttpParams();
    return this.toPipe(this.http.get<DataResponse<PhotoMeta[]>>(`${this.urlRestAPI}/${id}/images`, {params: parameters}));
  }
}
