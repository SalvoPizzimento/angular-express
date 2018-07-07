/* CODED BY Salvatore Pizzimento FOR Angular-Express Project */

import { Injectable } from '@angular/core';
import { Observable } from 'rxjs/Rx';
import 'rxjs/Rx';
import { DayPilot } from 'daypilot-pro-angular';
import { HttpClient } from "@angular/common/http";

@Injectable()
export class DataService {

  constructor(private http : HttpClient){
  }

  getPrenotazioni(from: DayPilot.Date, to: DayPilot.Date): Observable<any[]> {
    return this.http.get("/api/backend_prenotazioni.php?from=" + from.toString() + "&to=" + to.toString()) as Observable<any>;
  }

  getAule(): Observable<any[]> {
    return this.http.get("/api/backend_aule.php") as Observable<any>;
  }

  creaPrenotazione(params: CreateEventParams): Observable<CreateEventResponse> {
    return this.http.post("/api/backend_crea.php", params).map((response:any) => {
      return {
        id: response.id,
        start: params.start,
        end: params.end,
        resource: params.resource,
        text: params.text
      };
    });
  }

  eliminaPrenotazione(id: string): Observable<any> {
    return this.http.post("/api/backend_elimina.php", {id: id});
  }
}

export interface CreateEventParams {
  start: DayPilot.Date;
  end: DayPilot.Date;
  text: string;
  resource: string;
}

export interface CreateEventResponse {
  start: DayPilot.Date;
  end: DayPilot.Date;
  text: string;
  resource: string;
  id: string;
}