/**
 * Created by BaoHoang on 8/22/2017.
 */
import {ChartDataSets, ChartOptions} from 'chart.js';
import {Color} from 'ng2-charts';

export class ChartMetadata {
  data: ChartDataSets[];
  labels: any[];
  options: (ChartOptions & { annotation: any });
  colors: Color[];
  legend: boolean;
  type: string;
}
