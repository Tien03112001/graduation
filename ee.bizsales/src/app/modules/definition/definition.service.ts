import {Injectable} from '@angular/core';
import {HttpClient, HttpErrorResponse} from '@angular/common/http';
import {ToasterService} from 'angular2-toaster';
import {catchError, map, tap} from 'rxjs/operators';
import {environment} from '../../../environments/environment';
import {Observable} from 'rxjs/Observable';
import {ChannelMeta} from '../../modules/channel/channel.meta';
import {SaleOrderStatus} from '../../modules/sale-order-status/sale-order-status.meta';
import {SaleOrderShippingStatusMeta} from '../../modules/sale-order-shipping-status/sale-order-shipping-status.meta';
import {ShippingProvinceMeta} from '../../modules/shipping-province/shipping-province.meta';
import {PaymentTypeMeta} from '../../modules/payment-type/payment-type.meta';
import {forkJoin} from 'rxjs/observable/forkJoin';
import {StorageUtil} from '../../core';

@Injectable()
export class DefinitionService {

  public urlRestAPI: string;


  constructor(private http: HttpClient, private toast: ToasterService) {
    let domain = (new URL(environment.api_url));
    this.setURLRestAPI(`${domain.origin}/api/modules/definition`);
  }

  start() {
    let obs: Observable<any>[] = [];
    Object.getOwnPropertyNames(Object.getPrototypeOf(this)).forEach(functionName => {
      if (functionName.startsWith('load')) {
        obs.push(this[functionName]());
      }
    });
    return forkJoin(obs).toPromise();
  }

  private allPaymentTypes: PaymentTypeMeta[];

  loadAllPaymentTypes(): Observable<PaymentTypeMeta[]> {
    if (this.allPaymentTypes) {
      return Observable.of(this.allPaymentTypes);
    } else {
      return this.http.get<any>(`${this.urlRestAPI}/payment_types`, {params: {}})
        .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']), tap(data => this.allPaymentTypes = data));
    }
  }

  private allProvinces: ShippingProvinceMeta[];

  loadAllProvinces(): Observable<ShippingProvinceMeta[]> {
    if (this.allProvinces) {
      return Observable.of(this.allProvinces);
    } else {
      return this.http.get<any>(`${this.urlRestAPI}/shipping_provinces`, {params: {}})
        .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']), tap(data => this.allProvinces = data));
    }
  }

  private allSaleOrderStatuses: SaleOrderStatus[];

  loadAllSaleOrderStatuses(): Observable<SaleOrderStatus[]> {
    if (this.allSaleOrderStatuses) {
      return Observable.of(this.allSaleOrderStatuses);
    } else {
      return this.http.get<any>(`${this.urlRestAPI}/sale_order_statuses`, {params: {}})
        .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']), tap(data => this.allSaleOrderStatuses = data));
    }
  }

  private allSaleOrderShippingStatuses: SaleOrderShippingStatusMeta[];

  loadAllSaleOrderShippingStatuses(): Observable<SaleOrderShippingStatusMeta[]> {
    if (this.allSaleOrderShippingStatuses) {
      return Observable.of(this.allSaleOrderShippingStatuses);
    } else {
      return this.http.get<any>(`${this.urlRestAPI}/sale_order_shipping_statuses`, {params: {}})
        .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']), tap(data => this.allSaleOrderShippingStatuses = data));
    }
  }

  private allChannels: ChannelMeta[];

  loadAllChannels(): Observable<ChannelMeta[]> {
    if (this.allChannels) {
      return Observable.of(this.allChannels);
    } else {
      return this.http.get<any>(`${this.urlRestAPI}/channels`, {params: {}})
        .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']), tap(data => this.allChannels = data));
    }
  }

  setURLRestAPI(newURL: string): void {
    if (newURL) {
      this.urlRestAPI = newURL;
    }
  }

  handleErrorRequest(err: HttpErrorResponse) {
    if (err.error instanceof Error) {
      this.toastError('Client phản hồi', 'Lỗi xảy ra');
    } else {
      if (err.status == 401) {
        StorageUtil.clear();
      }
      if (err.status == 500) {
        this.toastError('Hệ thống phản hồi', `${err.error['message']}`);
      } else {
        this.toastError('Hệ thống phản hồi', `${err.message}`);
      }
    }
    return Observable.empty<any>();
  }

  toastError(title: string, content?: string) {
    this.toast.pop('error', `${title}`, content ? content : 'Thất bại');
  }

}
