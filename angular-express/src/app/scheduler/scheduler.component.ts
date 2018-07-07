/* CODED BY Salvatore Pizzimento FOR Angular-Express Project */

import { Component, ViewChild, AfterViewInit } from "@angular/core";
import { DayPilot, DayPilotSchedulerComponent } from "daypilot-pro-angular";
import { DataService, CreateEventParams } from "./data.service";

@Component({
  selector: 'scheduler-component',
  templateUrl:'./scheduler.component.html',
  styleUrls: ['./scheduler.component.css']
})

export class SchedulerComponent implements AfterViewInit {

  @ViewChild("scheduler")
  scheduler: DayPilotSchedulerComponent;

  events: any[] = [];

  //variabile per l'autenticazione
  auth: boolean = false;
  username: string = null;
  password: string = null;

  //configurazioni dello scheduler DayPilot
  config: any = {
    eventHeight: 40,
    cellWidthSpec: "Fixed",
    cellWidth: 100,
    timeHeaders: [{"groupBy":"Day","format":"dddd, d MMMM yyyy"},{"groupBy":"Hour","format":"H:mm"},{"groupBy":"Cell","format":" "}],
    scale: "CellDuration",
    cellDuration: 30,
    days: 30,
    startDate: DayPilot.Date.today().firstDayOfWeek(),
    businessBeginsHour: 8,
    businessEndsHour: 20,
    showNonBusiness: false,
    timeRangeSelectedHandling: "Disabled",
    scrollTo: new DayPilot.Date(),
    heightSpec: "Max",
    height: 500,
    rowHeaderColumns: [{title: "Aule"}],
    durationBarVisible: false,
    eventMoveHandling: "Disable",
    
    //evento che gestisce la creazione della prenotazione
    onTimeRangeSelected: args => {
      let component = this;
      DayPilot.Modal.prompt("Inserire materia e docente per prenotare l'aula:", "Materia (docente)").then(function(modal) {
          var dp = args.control;
          dp.clearSelection();
          if (!modal.result) { return; }
          var params: CreateEventParams = {
            start: args.start,
            end: args.end,
            resource: args.resource,
            text: modal.result
          };
          if(params.start.getDay() == params.end.getDay()){
            component.ds.creaPrenotazione(params).subscribe(result => {
              dp.events.add(new DayPilot.Event(result));
            });
          }
          else{
            DayPilot.Modal.alert("Impossibile prenotare l'aula per questa fascia oraria. Riprovare.");
          }
      });
    },
    //evento che gestisce l'eliminazione di una prenotazione esistente
    onEventDelete: args => {
      DayPilot.Modal.alert("Prenotazione eliminata.");
      this.ds.eliminaPrenotazione(args.e.id()).subscribe(result => this.scheduler.control);
    },
  };

  constructor(private ds: DataService) { }

  //funzione per inizializzare l'aulario
  ngAfterViewInit(): void {
    this.ds.getAule().subscribe(result => this.config.resources = result);
    var from = this.scheduler.control.visibleStart();
    var to = this.scheduler.control.visibleEnd();
    this.ds.getPrenotazioni(from, to).subscribe(result => {
      this.events = result;
    });
  }

  //funzione per il controllo dell'username inserito nel form di login
  checkUsername(): boolean {
      return (<HTMLInputElement>document.getElementById("username")).value=='prof'? true : false
  }

  //funzione per il controllo della password inserita nel form di login
  checkPassword(): boolean {
      return (<HTMLInputElement>document.getElementById("password")).value=='prof123' ? true : false
  }

  //funzione per controllare gestire il comportamento del form di login/logout
  isAuth(): boolean { return this.auth; }

  //funzione per permettere modifiche nell'aulario
  logIn(): void {
    if(this.checkUsername() && this.checkPassword()){
      this.auth = true;
      this.scheduler.config.eventDeleteHandling = "Update";
      this.scheduler.config.timeRangeSelectedHandling = "Enabled";
      this.username = (<HTMLInputElement>document.getElementById("username")).value;
      this.password = (<HTMLInputElement>document.getElementById("password")).value;
    }
    else{
      DayPilot.Modal.alert("Login fallito! Riprovare.");
    }
  }

  //funzione di logout
  logOut(): void {
    this.auth = false;
    this.scheduler.config.eventDeleteHandling = "Disabled";
    this.scheduler.config.timeRangeSelectedHandling = "Disabled";
  }
}
