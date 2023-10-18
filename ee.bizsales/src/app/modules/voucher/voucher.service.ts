import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {ToasterService} from 'angular2-toaster';
import {VoucherMeta} from './voucher.meta';
import {AbstractCRUDService, TitleService} from '../../core';

@Injectable()
export class VoucherService extends AbstractCRUDService<VoucherMeta> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'voucher', 'vouchers');
  }

}
