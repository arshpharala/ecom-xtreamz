/**
 * IDB Handler
 * Simple wrapper for IndexedDB to store customization images
 */
const dbName = "XtremezStoreDB";
const storeName = "customization_images";
const dbVersion = 1;

const IDB = {
  db: null,

  init: function () {
    return new Promise((resolve, reject) => {
      if (this.db) {
        resolve(this.db);
        return;
      }

      const request = indexedDB.open(dbName, dbVersion);

      request.onupgradeneeded = (event) => {
        const db = event.target.result;
        if (!db.objectStoreNames.contains(storeName)) {
          db.createObjectStore(storeName, { keyPath: "id" });
        }
      };

      request.onsuccess = (event) => {
        this.db = event.target.result;
        resolve(this.db);
      };

      request.onerror = (event) => {
        console.error("IDB Error:", event.target.error);
        reject(event.target.error);
      };
    });
  },

  save: function (id, files) {
    return this.init().then((db) => {
      return new Promise((resolve, reject) => {
        const transaction = db.transaction([storeName], "readwrite");
        const store = transaction.objectStore(storeName);

        // Store as an object with the customization ID and the array of file blobs
        const request = store.put({ id: id, files: files, timestamp: Date.now() });

        request.onsuccess = () => resolve(true);
        request.onerror = (e) => reject(e.target.error);
      });
    });
  },

  get: function (id) {
    return this.init().then((db) => {
      return new Promise((resolve, reject) => {
        const transaction = db.transaction([storeName], "readonly");
        const store = transaction.objectStore(storeName);
        const request = store.get(id);

        request.onsuccess = () => {
          resolve(request.result ? request.result.files : null);
        };
        request.onerror = (e) => reject(e.target.error);
      });
    });
  },

  getAll: function () {
    return this.init().then((db) => {
      return new Promise((resolve, reject) => {
        const transaction = db.transaction([storeName], "readonly");
        const store = transaction.objectStore(storeName);
        const request = store.getAll();

        request.onsuccess = () => resolve(request.result);
        request.onerror = (e) => reject(e.target.error);
      });
    });
  },

  delete: function (id) {
    return this.init().then((db) => {
      return new Promise((resolve, reject) => {
        const transaction = db.transaction([storeName], "readwrite");
        const store = transaction.objectStore(storeName);
        const request = store.delete(id);

        request.onsuccess = () => resolve(true);
        request.onerror = (e) => reject(e.target.error);
      });
    });
  }
};

// Expose globally
window.IDB = IDB;
