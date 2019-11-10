let MapyCZ = {};

/**
 * base for picker and control
 * @type {MapyCZ.BaseMap}
 */
MapyCZ.BaseMap = class
{
    /**
     * constructor
     * @param document
     * @param settings
     */
    constructor(document, settings)
    {
        this.settings = settings;
        this.document = document;
    }

    /**
     * set map size
     */
    setMapSize()
    {
        let mapElement = this.document.getElementById(this.settings.mapId);
        mapElement.style.width = this.settings.width.toString() + this.settings.units;
        mapElement.style.height = this.settings.height.toString() + this.settings.units;
    }

    /**
     * set map center
     * @returns {*}
     */
    setupMapCenter()
    {
        return SMap.Coords.fromWGS84(this.settings.center.longitude, this.settings.center.latitude);
    }

    /**
     * create map
     * @returns {SMap}
     */
    createMap()
    {
        return new SMap(JAK.gel(this.settings.mapId), this.mapCenter, this.settings.defaultZoom);
    }

    /**
     * @param map
     * @returns {SMap.Layer.Marker}
     */
    createMarkerLayer(map)
    {
        let markerLayer = new SMap.Layer.Marker();
        map.addLayer(markerLayer);
        markerLayer.enable();
        return markerLayer;
    }
};

/**
 * map control
 * @type {MapyCZ.MapControl}
 */
MapyCZ.MapControl = class extends MapyCZ.BaseMap
{
    /**
     * run map
     */
    init()
    {
        // set map size
        this.setMapSize();

        // set map center
        this.mapCenter = this.setupMapCenter();

        // create map
        this.map = this.createMap();

        // setup default layer
        this.map.addDefaultLayer(this.settings.mapType).enable();

        // add controls
        this.addControls();

        // add markers
        this.addMarkers();
    }

    /**
     * add controls
     */
    addControls()
    {
        if (this.settings.controls === true) {
            this.map.addDefaultControls();
        } else if (typeof(this.settings.controls) === "object") {
            // TODO
        }
    }

    /**
     * add markers
     */
    addMarkers()
    {
        if (Array.isArray(this.settings.markers) === true && this.settings.markers.length > 0) {
            const markerLayer = this.createMarkerLayer(this.map);
            for (let m of this.settings.markers) {
                const mSettings = JSON.parse(m.toString());
                const mCoords = SMap.Coords.fromWGS84(mSettings.longitude, mSettings.latitude);
                this.map.setCenter(mCoords, true);
                const marker = new SMap.Marker(mCoords, false, mSettings);
                markerLayer.addMarker(marker);
            }
        }
    }
};

MapyCZ.MapPicker = class extends MapyCZ.BaseMap
{
    /**
     * run map picker
     */
    init()
    {
        // set map size
        this.setMapSize();

        // set map center
        this.mapCenter = this.setupMapCenter();

        // create map
        this.map = this.createMap();

        // setup default layer
        this.map.addDefaultLayer(this.settings.mapType).enable();

        // add controls
        this.map.addDefaultControls();

        // add marker layer
        this.markerLayer = this.createMarkerLayer(this.map);

        // add value marker (if set)
        this.addValueMarker();

        // add click event listener
        this.map.getSignals().addListener(this.window, "map-click", this.pickGPS);
    }

    /**
     * pick GPS coordinates, add into input and set marker
     * @param e
     * @param elm
     */
    pickGPS = (e, elm) => {
        // select input element
        let gpsInput = this.document.getElementById(this.settings.formControlId);

        // load coordinates
        let coords = SMap.Coords.fromEvent(e.data.event, this.map);

        // remove all markers
        this.markerLayer.removeAll();

        // add new marker
        let options = {};
        let marker = new SMap.Marker(coords, "myMarker", options);
        this.markerLayer.addMarker(marker);

        // add values to inout element
        let latitude = coords.toWGS84()[1];
        let longitude = coords.toWGS84()[0];
        gpsInput.value = latitude.toString() + " " + longitude.toString();
    };

    /**
     * add value of map picker if set
     */
    addValueMarker()
    {
        if (this.settings.valueLatitude !== undefined && this.settings.valueLongitude !== undefined) {
            // create coordinates depends on value
            const mCoords = SMap.Coords.fromWGS84(this.settings.valueLongitude, this.settings.valueLatitude);
            // set map center to value
            this.map.setCenter(mCoords, true);
            // add marker
            const marker = new SMap.Marker(mCoords);
            this.markerLayer.addMarker(marker);
        }
    }
};

MapyCZ.Factory = class
{
    /**
     * @param document
     * @param settings
     * @returns {MapPicker}
     */
    static createMapPicker(document, settings)
    {
        return new MapyCZ.MapPicker(document, settings);
    }

    /**
     * @param document
     * @param settings
     * @returns {MapControl}
     */
    static createMapControl(document, settings)
    {
        return new MapyCZ.MapControl(document, settings);
    }
};
