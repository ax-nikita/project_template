class apiRequest extends axRequest {
  constructor(type, saveLoadData = true) {
    super('api/', { saveLoadData: saveLoadData });
    this.api_type = type;
  }
  execute(data, func = () => { }) {
    let
      prepared_data = {
        'type': this.api_type,
        'data': JSON.stringify(data)
      };
    super.execute(prepared_data, func);
  }
}