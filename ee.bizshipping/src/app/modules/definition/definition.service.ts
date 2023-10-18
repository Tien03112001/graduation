import {Injectable} from '@angular/core';
import {HttpClient, HttpParams} from '@angular/common/http';
import {ToasterService} from 'angular2-toaster';
import {catchError, map} from 'rxjs/operators';
import {environment} from '../../../environments/environment';
import {ShippingUnitMeta} from '../shipping-unit/shipping-unit.meta';
import {ShippingStoreMeta} from '../shipping-store/shipping-store.meta';
import {ShippingServiceMeta} from '../shipping-service/shipping-service.meta';
import { AbstractCRUDService, TitleService } from '../../core';

@Injectable()
export class DefinitionService extends AbstractCRUDService<any> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'Biến hằng', 'definition');
    let domain = (new URL(environment.api_url));
    this.setURLRestAPI(`${domain.origin}/api/modules/definition`);
  }

  loadAllSaleOrderStatus() {
    return this.http.get<any>(`${this.urlRestAPI}/sale_order_statuses`, {params: {}})
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }

  loadAllSaleOrderShippingStatus() {
    return this.http.get<any>(`${this.urlRestAPI}/sale_order_shipping_statuses`, {params: {}})
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }

  loadAllChannel() {
    return this.http.get<any>(`${this.urlRestAPI}/channels`, {params: {}})
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }

  loadAllShippingUnit() {
    return this.http.get<ShippingUnitMeta[]>(`${this.urlRestAPI}/shipping_units`, {params: {}})
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }

  loadShippingStoreByUnit(unitId: number) {
    let parameters: HttpParams = new HttpParams({
      fromObject: {'unit_id': unitId.toString()}
    });
    return this.http.get<ShippingStoreMeta[]>(`${this.urlRestAPI}/shipping_stores`, {params: parameters})
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }

  loadShippingServiceByUnit(unitId: number) {
    let parameters: HttpParams = new HttpParams({
      fromObject: {'unit_id': unitId.toString()}
    });
    return this.http.get<ShippingServiceMeta[]>(`${this.urlRestAPI}/shipping_services`, {params: parameters})
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }

}
