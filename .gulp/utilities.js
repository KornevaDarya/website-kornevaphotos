const fs = require("fs");
const path = require("path");
const rename = require("gulp-rename");
const changed = require("gulp-changed");
const { sync: deleteFile } = require("rimraf");
const { obj: through } = require("through2");
const { v4: uuid } = require("uuid");

const getRandomHash = () => uuid().substring(0, 8);

const addMinSuffix = () => rename({ suffix: ".min" });

const addRandomHash = () => rename(file => {
    const hash = getRandomHash();
    file.basename = `${file.basename}.${hash}`;
});

const findPreviousVersion =(folder, fileName) => {
    const filesInFolder = fs.readdirSync(folder);
    const [previosVersion] = filesInFolder.filter(fileFullName => fileFullName.includes(fileName));
    return previosVersion;
}

const deletePreviosVersion = (folder) => through(function(file, encoding, done) {
    const previosVersion = findPreviousVersion(folder, file.stem);

    if (previosVersion) {
        deleteFile(path.join(folder, previosVersion));
    }

    done(null, file);
});

const filterChanged = folder => changed(folder, {
    hasChanged: changed.compareContents,
    transformPath: filePath => {
        const fileName = path.basename(filePath, path.extname(filePath));
        const previosVersion = findPreviousVersion(folder, fileName);
        return previosVersion ? path.join(path.dirname(filePath), previosVersion) : "";
    }
});

const logger = (message) => through(function(file, encoding, done) {
    console.log(message, file.basename);
    done(null, file);
});

module.exports = {
    rename: {
        addMinSuffix,
        addRandomHash
    },
    filterChanged,
    deletePreviosVersion,
    logger
}