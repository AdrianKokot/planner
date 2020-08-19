class DateTimeConverter {

  static convertToTimeString(date) {
    return new Date(date).toLocaleTimeString("en-UK", {
      timeZone: "Europe/Berlin",
      hour12: false,
      hour: "2-digit",
      minute: "2-digit",
    })
  }

  static convertToDateString(date) {
    return new Date(date).toLocaleDateString("pl", { timeZone: "Europe/Berlin" })
  }

  static convertToDateTimeString(datetime) {
    return new Date(datetime)
      .toLocaleDateString("pl", {
        day: "2-digit",
        month: "2-digit",
        year: "numeric",
      })
      .split(".")
      .reverse()
      .join("-") +
      "T" +
      new Date(datetime).toTimeString().slice(0, 8)
  }

  static eventTime(event = null) {
    if (event != null) {
      const tdObj = {
        timeStart: this.convertToTimeString(event.start),
        timeEnd: this.convertToTimeString(event.end),
        dateStart: this.convertToDateString(event.start),
        dateEnd: this.convertToDateString(event.end),
      };

      return tdObj.dateStart != tdObj.dateEnd
        ? `${tdObj.timeStart} ${tdObj.dateStart} - ${tdObj.timeEnd} ${tdObj.dateEnd}`
        : `${tdObj.timeStart} - ${tdObj.timeEnd}, ${tdObj.dateStart}`;
    }
    return "";
  }
}

export default DateTimeConverter;
