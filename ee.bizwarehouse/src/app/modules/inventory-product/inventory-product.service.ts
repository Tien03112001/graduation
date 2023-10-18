import {Injectable} from '@angular/core';
import {HttpClient, HttpParams} from '@angular/common/http';
import {ToasterService} from 'angular2-toaster';
import {InventoryProductMeta} from './inventory-product.meta';
import {catchError, map} from 'rxjs/operators';
import { AbstractCRUDService, DataResponse, TitleService } from '../../core';

@Injectable()
export class InventoryProductService extends AbstractCRUDService<InventoryProductMeta> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'tồn kho', 'inventory_products');
  }

  add(item: InventoryProductMeta) {
    return this.http.post<DataResponse<InventoryProductMeta>>(`${this.urlRestAPI}/${item.id}/add_quantity`, item)
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }

  printBarcode(id: number, quantity: number) {
    let parameters: HttpParams = new HttpParams({
      fromObject: {quantity: quantity.toString()}
    });
    return this.http.get<DataResponse<any>>(`${this.urlRestAPI}/${id}/printBarcode`, {params: parameters})
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }

}
