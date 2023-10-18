import {ExportingNoteDetailMeta} from '../exporting-note-detail/exporting-note-detail.meta';

export class ExportingNoteMeta {
  id: number;
  name: string;
  creator_name: string;
  is_approved: number;

  details: ExportingNoteDetailMeta[];
}
