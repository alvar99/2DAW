class Hotel {

    name = '';
    number_of_rooms = 0;
    rooms = [];

    constructor(name, rooms) {
        this.name = name;
        this.rooms = rooms;
        this.number_of_rooms = rooms.length;
    }

}