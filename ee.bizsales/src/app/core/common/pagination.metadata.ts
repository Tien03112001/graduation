import {PaginationOutput} from './pagination-output.metadata';

/**
 * Created by BaoHoang on 8/22/2017.
 */

export class AppPagination {
  totalItems: number;
  currentPage: number;
  numPages: number;
  maxSize: number;
  itemsPerPage: number;

  constructor() {
    this.totalItems = 0;
    this.currentPage = 1;
    this.numPages = 0;
    this.maxSize = 5;
    this.itemsPerPage = 20;
  }

  set(output: PaginationOutput<any>) {
    this.totalItems = output.total;
    this.currentPage = output.current_page;
    this.numPages = output.last_page;
  }

}
