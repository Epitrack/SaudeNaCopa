package br.com.epitrack.healthycup.classes;

import java.text.DateFormat;
import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.Date;

public class Jogo {
	private String rodada;
	private String grupo;
	private String times;
	private String arena;
	public Jogo(String grupo, String rodada, String times, String horario, String arena) throws ParseException {
		this.rodada = rodada;
		this.grupo = grupo;
		this.times = times;
		SimpleDateFormat format = new SimpleDateFormat("dd/MM/yyyy - HH:mm"); 
		Date data = format.parse(horario);
		this.horario = new SimpleDateFormat("EEEE dd/MM/yyyy - HH:mm").format(data);
		this.arena = arena;
		
	}
	public String getRodada() {
		return rodada;
	}
	public void setRodada(String rodada) {
		this.rodada = rodada;
	}
	public String getGrupo() {
		return grupo;
	}
	public void setGrupo(String grupo) {
		this.grupo = grupo;
	}
	public String getTimes() {
		return times;
	}
	public void setTimes(String times) {
		this.times = times;
	}
	public String getHorario() {
		return horario;
	}
	public void setHorario(String horario) {
		this.horario = horario;
	}
	public String getArena() {
		return arena;
	}
	public void setArena(String arena) {
		this.arena = arena;
	}
	private String horario;

}
