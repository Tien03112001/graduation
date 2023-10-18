import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {ToasterService} from 'angular2-toaster';
import {AbstractCRUDService, TitleService} from '../../core';
import {PromotionMeta} from './promotion.meta';
import {environment} from '../../../environments/environment';

@Injectable()
export class PromotionService extends AbstractCRUDService<PromotionMeta> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'Quản lý kênh', 'promotions');
    this.setApiUrl(environment.api_url);
  }

}
